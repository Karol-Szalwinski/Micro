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
        $defaultdocument = $em->getRepository('MicroBundle:Document')->findOneBy(['id' => 1]);
        $document = clone $defaultdocument;
        $document->setDeviceShortlistPosition($building->getDeviceShortlistPosition());
        $document->setInspectionDate(new \DateTime());
        $document->setNextInspectionDate(new \DateTime('now + 6 month'));


        $form = $this->createForm('MicroBundle\Form\DocumentType', $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            $building->addDocument($document);
            $document->setBuilding($building);


            $em = $this->getDoctrine()->getManager();
            //add test position to document
            foreach ($defaultdocument->getDocPositions() as $defDocPosition) {
                $DocPosition = clone $defDocPosition;
                $DocPosition->setDocument($document);
                $document->addDocPosition($DocPosition);
                $em->persist($DocPosition);
            }

            //add DocDevice to document

            foreach ($building->getBuildDevices() as $device) {
                if (!$device->getDel()) {
                    $docDevices = new DocDevice();
                    $docDevices->setNumber($device->getNumber());
                    $docDevices->setShortname($device->getShortname());
                    $docDevices->setLoopNo($device->getLoopNo());

                    $docDevices->setBuildDevice($device);
                    $device->addDocDevice($docDevices);

                    $docDevices->setDocument($document);
                    $document->addDocDevice($docDevices);

                    $em->persist($docDevices);
                }
            }


            $em->persist($document);
            $em->flush();

            return $this->redirectToRoute('document_show', array('id' => $document->getId()));
        }

        return $this->render('document/new.html.twig', array('document' => $document, 'building' => $building, 'form' => $form->createView(),));
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
        $docInspectors = $pdfSettings->getdocument()->getInspectors();
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

        foreach ($document->getDocumentInspectors() as $inspector) {
            if ($inspector->getPrototype()) {
                $prototypeId = $inspector->getPrototype();
                $prototype = $em->getRepository('MicroBundle:Inspector')->findOneBy(['id' => $prototypeId]);
                if ($prototype instanceof Inspector) {
                    $document->addTempInspector($prototype);
                }
            }
        }

        $editForm = $this->createForm('MicroBundle\Form\DocumentType', $document);

        $editForm->handleRequest($request);


        if ($editForm->isSubmitted() && $editForm->isValid()) {
            //clear Document Inspectors
            foreach ($document->getDocumentInspectors() as $documentInspector) {

                $document->removeDocumentInspector($documentInspector);

                $em->remove($documentInspector);

            }
            //add Document Inspectors
            foreach ($document->getTempInspectors() as $inspector) {
                $docInspector = new DocumentInspector();
                $docInspector->setName($inspector->getName());
                $docInspector->setSurname($inspector->getSurname());
                $docInspector->setLicense($inspector->getLicense());
                $docInspector->setdocument($document);
                $docInspector->setPrototype($inspector->getId());
                $document->addDocumentInspector($docInspector);
                $document->removeTempInspector($inspector);
            }

            $em->flush();
            return $this->redirectToRoute('document_show', array('id' => $document->getId()));
        }

        return $this->render('document/edit.html.twig', array('document' => $document, 'edit_form' => $editForm->createView(),));

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

    private function adddocDevice(Document $document, BuildDevice $device)
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

}
