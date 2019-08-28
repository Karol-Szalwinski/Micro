<?php

namespace MicroBundle\Controller;

use MicroBundle\Entity\Building;
use MicroBundle\Entity\Document;
use MicroBundle\Entity\BuildDevice;
use MicroBundle\Entity\DocDevice;
use MicroBundle\Entity\Inspector;
use MicroBundle\Entity\PdfSettings;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * document controller.
 *
 * @Route("document")
 */
class DocumentController extends Controller
{
    /**
     * Lists all document entities.
     *
     * @Route("/", name="document_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $documents = $em->getRepository('MicroBundle:Document')->findAllExceptId(1);

        return $this->render('document/index.html.twig', array('documents' => $documents,));
    }

    /**
     * Creates a new document entity.
     *
     * @Route("/new/{building}", name="document_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Building $building, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $defaultDocument = $em->getRepository('MicroBundle:Document')->findOneBy(['id' => 1]);
        $document = clone $defaultDocument;
        $document->setDeviceShortlistPosition($building->getDeviceShortlistPosition());
        $document->setInspectionDate(new \DateTime());
        $document->setNextInspectionDate(new \DateTime('now + 6 month'));
        $names = ["Przegląd urządzeń SPP", "Protokół odbioru", "Protokół przekazania", "Protokół ze szkolenia"];

        $form = $this->createForm('MicroBundle\Form\DocumentType', $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $building->addDocument($document);
            $document->setBuilding($building);


            $em = $this->getDoctrine()->getManager();
            //add test position to document
            foreach ($defaultDocument->getDocPositions() as $defDocPosition) {
                $DocPosition = clone $defDocPosition;
                $DocPosition->setDocument($document);
                $document->addDocPosition($DocPosition);
                $em->persist($DocPosition);
            }

            //add DocDevice to document


            $this->generateDocDevices([], $document, $em, true);


            $em->persist($document);
            $em->flush();

            return $this->redirectToRoute('document_show', array('id' => $document->getId()));
        }

        return $this->render('document/new.html.twig', array('document' => $document, 'building' => $building, 'names' => $names, 'form' => $form->createView(),));
    }

    /**
     * Finds and displays a document entity.
     *
     * @Route("/{id}", name="document_show")
     * @Method("GET")
     * @param Request $request
     * @param Document $document
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request, Document $document)
    {
// todo update
//        $this->container->get('micro')->updateLastServiceDatedocument($document);
        $em = $this->getDoctrine()->getManager();


        $pdfSettings = $document->getPdfSettings();
        if (!$pdfSettings) {
            $pdfSettings = new PdfSettings($document);
            $document->setPdfSettings($pdfSettings);
        }
        //todo refactor this code
        $docInspectors = $document->getInspectors();
        $inspectors = [];
        foreach ($docInspectors as $docInspector) {
            $inspectors[$docInspector->getName() . " " . $docInspector->getSurname()] = $docInspector->getName() . " " . $docInspector->getSurname();
        }

        $options = ['inspectors' => $inspectors];
        $pdfForm = $this->createForm('MicroBundle\Form\PdfSettingsType', $pdfSettings, $options);

        $pdfForm->handleRequest($request);
        if ($pdfForm->isSubmitted() && $pdfForm->isValid()) {
            $em->persist($pdfSettings);
            $em->flush();
            return $this->redirectToRoute('fire_inspection_pdf', array('document' => $document->getId()));

        }

        return $this->render('document/show.html.twig', array('document' => $document, 'pdf_form' => $pdfForm->createView(),));
    }

    /**
     * Displays a form to edit an existing document entity.
     *
     * @Route("/{id}/edit", name="document_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Document $document)
    {
        $em = $this->getDoctrine()->getManager();
        $names = ["Przegląd urządzeń SPP", "Protokół odbioru", "Protokół przekazania", "Protokół ze szkolenia"];

        $editForm = $this->createForm('MicroBundle\Form\DocumentType', $document);

        $editForm->handleRequest($request);


        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em->flush();
            return $this->redirectToRoute('document_show', array('id' => $document->getId()));
        }

        return $this->render('document/edit.html.twig', array('document' => $document, 'names' => $names, 'edit_form' => $editForm->createView(),));

    }

    /**
     * Displays a form to edit an existing document entity summary.
     *
     * @Route("/{id}/editsum", name="document_edit_summary")
     * @Method({"GET", "POST"})
     */
    public function editSumAction(Request $request, Document $document)
    {

        $editForm = $this->createForm('MicroBundle\Form\DocumentSummaryType', $document);

        $editForm->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em->flush();

            return $this->redirectToRoute('document_show', array('id' => $document->getId()));
        }

        return $this->render('document/editsum.html.twig', array('document' => $document, 'edit_form' => $editForm->createView(),));
    }

    /**
     * Show Docement devices in loop.
     *
     * @Route("/{id}/devices/{loop}", name="document_devices")
     * @Method({"GET", "POST"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function devicesAction(Document $document, $loop, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $this->generateDocDevices($loop, $document, $em, false);

        $devices = $em->getRepository('MicroBundle:Document')->findDocumentDevices($document->getId(), $loop);


        //todo refactor
        $devicesTypes = $em->getRepository('MicroBundle:Device')->findAll();
        $shortnames = [];
        foreach ($devicesTypes as $device) {
            $shortnames[] = $device->getShortname();
        }


        return $this->render('document/devices.html.twig', array('document' => $document, 'devices' => $devices, 'miss_devices' => [], 'shortnames' => $shortnames, 'loop_no' => $loop

        ));

    }

    /**
     * Finds and displays a document entity.
     *
     * @Route("/{id}/add-new-device/{deviceId}",  defaults={"deviceId"=0}, name="add_new_device")
     * @Method("POST")
     */
    public function addNewDeviceAction(Document $document, $deviceId)
    {
        $em = $this->getDoctrine()->getManager();

        if ($deviceId == 0) {
            foreach ($document->getBuilding()->getLoopDevs() as $loop) {

                $loopNullDev = $em->getRepository('MicroBundle:Document')->findNullDevicesBydocument($document->getId(), $loop->getId());
                foreach ($loopNullDev as $arrayDevice) {
                    $device = $em->getRepository('MicroBundle:BuildDevice')->findOneBy(['id' => $arrayDevice['id']]);
                    $this->adddocDevice($document, $device);
                }
            }

        } else {
            $device = $em->getRepository('MicroBundle:BuildDevice')->findOneBy(['id' => $deviceId]);

            $this->adddocDevice($document, $device);

            //set loop number in session
            $this->get('session')->set('loop-number', $device->getLoopDev()->getNumber());
        }


        return $this->redirectToRoute('document_show', array('id' => $document->getId()));
    }

    private function addDocDevice(Document $document, BuildDevice $device)
    {
        $em = $this->getDoctrine()->getManager();
        $docDevices = new DocDevice();
        $docDevices->setNumber($device->getNumber());
        $docDevices->setShortname($device->getShortname());

        $docDevices->setBuildDevice($device);
        $device->adddocDevice($docDevices);

        $docDevices->setdocument($document);
        $document->adddocDevice($docDevices);

        $em->persist($docDevices);
        $em->flush();
    }

    /**
     * @param $loop
     * @param $document
     * @param $em
     */
    private function generateDocDevices($loop, $document, $em, $visible): void
    {

        if (is_array($loop)) {
            $missDevices = $em->getRepository('MicroBundle:Document')->findMissingDocumentDevices($document->getId(), $document->getBuilding()->getId());
        } else {
            $missDevices = $em->getRepository('MicroBundle:Document')->findMissingDocumentDevicesByLoop($document->getId(), $document->getBuilding()->getId(), $loop);
        }

        foreach ($missDevices as $device) {
            if ($device->{'del'} == 0) {
                $buildDevice = $em->getRepository('MicroBundle:BuildDevice')->findOneBy(['id' => $device->{'id'}]);
                $docDevices = new DocDevice();
                $docDevices->setNumber($buildDevice->getNumber());
                $docDevices->setShortname($buildDevice->getShortname());
                $docDevices->setLoopNo($buildDevice->getLoopNo());
                $docDevices->setVisible($visible);

                $docDevices->setBuildDevice($buildDevice);
                $buildDevice->addDocDevice($docDevices);

                $docDevices->setDocument($document);
                $document->addDocDevice($docDevices);

                $em->persist($docDevices);
                $em->flush();
            }
        }
    }

}
