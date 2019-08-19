<?php

namespace MicroBundle\Controller;

use Doctrine\ORM\EntityManager;
use MicroBundle\Entity\Building;
use MicroBundle\Entity\Client;
use MicroBundle\Entity\FireProtectionDevice;
use MicroBundle\Entity\LoopDev;
use MicroBundle\Entity\PdfDocument;
use MicroBundle\Services\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * Building controller.
 *
 * @Route("building")
 */
class BuildingController extends Controller
{
    /**
     * Lists all building entities.
     *
     * @Route("/", name="building_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $buildings = $em->getRepository('MicroBundle:Building')->findAll();

        return $this->render('building/index.html.twig', array('buildings' => $buildings,));
    }

    /**
     * Creates a new building entity.
     *
     * @Route("/new/{client}", name="building_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Client $client, Request $request)
    {
        $building = new Building();
        $building->setDeviceShortlistPosition("Centrala NAZWA \nCzujki optyczne dymu\nCzujki termiczne\nSygnalizatory optyczno-akustyczne\n" . "Ręczne ostrzegacze pożarowe\nDrukarki termiczne");
        $form = $this->createForm('MicroBundle\Form\BuildingType', $building);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $building->setClient($client);
            $client->addBuilding($building);
            $em = $this->getDoctrine()->getManager();
            $em->persist($building);
            $em->flush();

            return $this->redirectToRoute('building_show', array('id' => $building->getId()));
        }

        return $this->render('building/new.html.twig', array('building' => $building, 'client' => $client, 'form' => $form->createView(),));
    }

    /**
     * Finds and displays a building entity.
     *
     * @Route("/{id}", name="building_show")
     * @Method("GET")
     */
    public function showAction(Building $building, Request $request)
    {

        $loopDev = new LoopDev();
        //setting new loop number
        $loopDev->setNumber($building->countLoopDevsWithoutDel() + 1);
        $loopForm = $this->createForm('MicroBundle\Form\LoopDevType', $loopDev);
        $loopForm->handleRequest($request);

        $tempFireProtectionDevice = new Fireprotectiondevice();
        $editForm = $this->createForm('MicroBundle\Form\FireProtectionDeviceEditType', $tempFireProtectionDevice);
        $addForm = $this->createForm('MicroBundle\Form\FireProtectionDeviceType', $tempFireProtectionDevice);


        //Form to add Loop
        if ($loopForm->isSubmitted() && $loopForm->isValid()) {
            $quantityDevices = $loopDev->getQuantityDevices();

            $em = $this->getDoctrine()->getManager();
//check if loopDev exist
            $loopOldDev = $em->getRepository('MicroBundle:LoopDev')->findOneBy(['building' => $building->getId(), 'number' => $loopDev->getNumber()]);

            if ($loopOldDev instanceof LoopDev) {
                $loopDev = $loopOldDev;
                $loopDev->setDel(false);
                for ($i = 1; $i <= $quantityDevices; $i++) {
                    $fireProtectionDevice = $em->getRepository('MicroBundle:FireProtectionDevice')->findOneBy(['loopDev' => $loopDev->getId(), 'number' => $i]);
                    if ($fireProtectionDevice instanceof FireProtectionDevice) {
                        $fireProtectionDevice->setDel(false);

                    } else {
                        $fireProtectionDevice = new FireProtectionDevice();
                        $fireProtectionDevice->setNumber($i);
                        $fireProtectionDevice->setLoopDev($loopDev);
                        $loopDev->addFireProtectionDevice($fireProtectionDevice);
                        $em->persist($fireProtectionDevice);

                    }
                }

            } else {
                $building->addLoopDev($loopDev);
                $loopDev->setBuilding($building);


                for ($i = 1; $i <= $quantityDevices; $i++) {
                    $fireProtectionDevice = new FireProtectionDevice();
                    $fireProtectionDevice->setNumber($i);
                    $fireProtectionDevice->setLoopDev($loopDev);
                    $loopDev->addFireProtectionDevice($fireProtectionDevice);
                    $em->persist($fireProtectionDevice);
                }
                $em->persist($loopDev);
            }


            $em->flush();

            return $this->redirectToRoute('building_show', array('id' => $building->getId()));
        }
        $this->container->get('micro')->updateLastServiceDate($building);

        return $this->render('building/show.html.twig', array('building' => $building, 'form' => $addForm->createView(), 'loop_form' => $loopForm->createView(), 'edit_form' => $editForm->createView()));
    }

    /**
     * Displays a form to edit an existing building entity.
     *
     * @Route("/{id}/edit", name="building_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Building $building)
    {
        $deleteForm = $this->createDeleteForm($building);
        $editForm = $this->createForm('MicroBundle\Form\BuildingType', $building);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('building_index');
        }

        return $this->render('building/edit.html.twig', array('building' => $building, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Show building documents.
     *
     * @Route("/{id}/document", name="building_document")
     * @Method({"GET", "POST"})
     * @param Building $building
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function documentAction(Building $building, Request $request)
    {

        $fileUploader = $this->get('MicroBundle\Services\FileUploader');
        $pdf = new PdfDocument();
        $pdfForm = $this->createForm('MicroBundle\Form\PdfDocumentType', $pdf);
        $pdfForm->handleRequest($request);

        if ($pdfForm->isSubmitted() && $pdfForm->isValid()) {

            $pdfFile = $pdfForm['pdf']->getData();

            if ($pdfFile) {
                $pdfFileName = $fileUploader->upload($pdfFile);
                $pdf->setPdfFileName($pdfFileName);

                $building->addPdfDocument($pdf);
                $em = $this->getDoctrine()->getManager();
                $em->persist($pdf);
                $em->flush();

            }


            return $this->redirectToRoute('building_document', array('id' => $building->getId()));
        }

        return $this->render('building/document.html.twig', array('building' => $building, 'pdf_form' => $pdfForm->createView(),));

    }


    /**
     * @Route("/{id}/document/{pdfDocument}/delete", name="building_document_delete")
     * @Method({"POST"})
     * @param Building $building
     * @param PdfDocument $pdfDocument
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteDocumentAction(Building $building, PdfDocument $pdfDocument)
    {
        $em = $this->getDoctrine()->getManager();
        $building->removePdfDocument($pdfDocument);
        $em->remove($pdfDocument);
        $em->flush();
        return $this->redirectToRoute('building_document', array('id' => $building->getId()));
    }
}
