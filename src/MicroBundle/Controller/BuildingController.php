<?php

namespace MicroBundle\Controller;

use Doctrine\ORM\EntityManager;
use MicroBundle\Entity\Building;
use MicroBundle\Entity\Client;
use MicroBundle\Entity\BuildDevice;
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


        $tempFireProtectionDevice = new BuildDevice();
//        $editForm = $this->createForm('MicroBundle\Form\FireProtectionDeviceEditType', $tempFireProtectionDevice);
//        $addForm = $this->createForm('MicroBundle\Form\FireProtectionDeviceType', $tempFireProtectionDevice);


        //todo refactor this service
//        $this->container->get('micro')->updateLastServiceDate($building);

        return $this->render('building/show.html.twig',
            array('building' => $building,
//                'form' => $addForm->createView(),
//                'edit_form' => $editForm->createView()
            ));
    }

    /**
     * Displays a form to edit an existing building entity.
     *
     * @Route("/{id}/edit", name="building_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Building $building)
    {
        $editForm = $this->createForm('MicroBundle\Form\BuildingType', $building);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('building_index');
        }

        return $this->render('building/edit.html.twig', array('building' => $building, 'edit_form' => $editForm->createView() ));
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


    /**
     * Show building devices in loop.
     *
     * @Route("/{id}/devices/{loop}", name="building_devices")
     * @Method({"GET", "POST"})
     * @param Building $building
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function devicesAction(Building $building, $loop, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $buildDevices = $em->getRepository('MicroBundle:BuildDevice')->findBy(['building' => $building, 'loopNo' => $loop]);
        $devices=$em->getRepository('MicroBundle:Device')->findAll();
        $shortnames=[];
        foreach ($devices as $device) {
            $shortnames[]=$device->getShortname();
        }


        $loopForm = $this->createForm('MicroBundle\Form\LoopType', ['loop' => $loop]);
        $loopForm->handleRequest($request);
        if ($loopForm->isSubmitted() && $loopForm->isValid()) {
            $quantity = $loopForm->getData()['quantityDevices'];
            for($i=1; $i <= $quantity; $i ++){
                $device = new BuildDevice();
                $device->setLoopNo($loop);
                $device->setNumber($i);
                $device->setBuilding($building);
                $building->addBuildDevice($device);
                $em->persist($device);
            }
            $em->flush();
            return $this->redirectToRoute('building_devices', array('id' => $building->getId(), 'loop' => $loop));
        }

        return $this->render('building/devices.html.twig', array(
            'building' => $building,
            'devices' => $buildDevices,
            'shortnames' => $shortnames,
            'loop_no' => $loop,
            'loop_form' => $loopForm->createView(),
        ));

    }
}
