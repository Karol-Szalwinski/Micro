<?php

namespace MicroBundle\Controller;

use MicroBundle\Entity\Building;
use MicroBundle\Entity\DocumentInspector;
use MicroBundle\Entity\FireInspection;
use MicroBundle\Entity\InspectedDevice;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Fireinspection controller.
 *
 * @Route("fireinspection")
 */
class FireInspectionController extends Controller
{
    /**
     * Lists all fireInspection entities.
     *
     * @Route("/", name="fireinspection_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $fireInspections = $em->getRepository('MicroBundle:FireInspection')->findAll();

        return $this->render('fireinspection/index.html.twig', array(
            'fireInspections' => $fireInspections,
        ));
    }

    /**
     * Creates a new fireInspection entity.
     *
     * @Route("/new/{building}", name="fireinspection_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Building $building, Request $request)
    {
        $fireInspection = new Fireinspection();
        $form = $this->createForm('MicroBundle\Form\FireInspectionType', $fireInspection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $building->addFireInspection($fireInspection);
            $fireInspection->setBuilding($building);
            foreach ($fireInspection->getTempInspectors() as $inspector) {
                $docInspector = new DocumentInspector();
                $docInspector->setName($inspector->getName());
                $docInspector->setSurname($inspector->getSurname());
                $docInspector->setLicense($inspector->getLicense());
                $docInspector->setFireInspection($fireInspection);
                $fireInspection->addDocumentInspector($docInspector);
                $fireInspection->removeTempInspector($inspector);
            }

            $em = $this->getDoctrine()->getManager();

            $em->persist($fireInspection);
            $em->flush();

            return $this->redirectToRoute('fireinspection_show', array('id' => $fireInspection->getId()));
        }

        return $this->render('fireinspection/new.html.twig', array(
            'fireInspection' => $fireInspection,
            'building' => $building,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a fireInspection entity.
     *
     * @Route("/{id}", name="fireinspection_show")
     * @Method("GET")
     */
    public function showAction(FireInspection $fireInspection)
    {
        $deleteForm = $this->createDeleteForm($fireInspection);

        return $this->render('fireinspection/show.html.twig', array(
            'fireInspection' => $fireInspection,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing fireInspection entity.
     *
     * @Route("/{id}/edit", name="fireinspection_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, FireInspection $fireInspection)
    {

        $editForm = $this->createForm('MicroBundle\Form\FireInspectionType', $fireInspection);

//        dump($fireInspection); die();
        $editForm->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            //clear Document Inspectors
            foreach ($fireInspection->getDocumentInspectors() as $documentInspector) {

                $fireInspection->removeDocumentInspector($documentInspector);

                $em->remove($documentInspector);

            }
            //add Document Inspectors
            foreach ($fireInspection->getTempInspectors() as $inspector) {
                $docInspector = new DocumentInspector();
                $docInspector->setName($inspector->getName());
                $docInspector->setSurname($inspector->getSurname());
                $docInspector->setLicense($inspector->getLicense());
                $docInspector->setFireInspection($fireInspection);
                $fireInspection->addDocumentInspector($docInspector);
                $fireInspection->removeTempInspector($inspector);
            }

            $em->flush();
            return $this->redirectToRoute('fireinspection_show', array('id' => $fireInspection->getId()));
        }

        return $this->render('fireinspection/edit.html.twig', array(
            'fireInspection' => $fireInspection,
            'edit_form' => $editForm->createView(),
        ));

    }

    /**
     * Displays a form to edit an existing fireInspection entity summary.
     *
     * @Route("/{id}/editsum", name="fireinspection_edit_summary")
     * @Method({"GET", "POST"})
     */
    public function editSumAction(Request $request, FireInspection $fireInspection)
    {

        $editForm = $this->createForm('MicroBundle\Form\FireInspectionSummaryType', $fireInspection);

        $editForm->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em->flush();

            return $this->redirectToRoute('fireinspection_show', array('id' => $fireInspection->getId()));
        }

        return $this->render('fireinspection/editsum.html.twig', array(
            'fireInspection' => $fireInspection,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Create list of Inspected Devices.
     *
     * @Route("/{id}/loaddevices", name="fireinspection_load_devices")
     * @Method({"GET", "POST"})
     */
    public function loadDevicesAction(Request $request, FireInspection $fireInspection)
    {
        if ($fireInspection->getInspectedDevices()->isEmpty()) {

            $em = $this->getDoctrine()->getManager();

            $fireProtectionDevices = $fireInspection->getBuilding()->getFireProtectionDevices();

            foreach ($fireProtectionDevices as $device) {
                $inspectedDevice = new InspectedDevice();
                $inspectedDevice->setShortname($device->getShortname());
                $inspectedDevice->setLoopNo($device->getLoopNo());
                $inspectedDevice->setNumber($device->getNumber());
                $inspectedDevice->setFireProtectionDevice($device);
                $inspectedDevice->setFireInspection($fireInspection);
                $device->addInspectedDevice($inspectedDevice);
                $fireInspection->addInspectedDevice($inspectedDevice);
                $em->persist($inspectedDevice);


            }
            $em->flush();

        }
        return $this->redirectToRoute('fireinspection_show', array('id' => $fireInspection->getId()));


    }

    /**
     * Create list of Inspected Devices.
     *
     * @Route("/{fireInspection}/loadmissdevices", name="fireinspection_load_missed_devices")
     * @Method({"GET", "POST"})
     */
    public function loadMissedDevicesAction( $fireInspection, Request $request) {

        $em = $this->getDoctrine()->getManager();

        $fireInspection = $em->getRepository('MicroBundle:FireInspection')->findOneById($fireInspection);
            //all devices in building
        $fireProtectionDevices = $fireInspection->getBuilding()->getFireProtectionDevices();

        $missingInspectedDevices = [];
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);



        foreach ($fireProtectionDevices as $device) {
            $success = true;
            foreach ($fireInspection->getInspectedDevices() as $insDevice) {
                if ($device == $insDevice->getFireProtectionDevice()) {
                    $success = false;
                }
            }
            if ($success) {
                $inspectedDevices = new InspectedDevice();
                $inspectedDevices->setId($device->getId());
                $inspectedDevices->setLoopNo($device->getLoopNo());
                $inspectedDevices->setNumber($device->getNumber());
                $inspectedDevices->setShortname($device->getShortname());

                array_push($missingInspectedDevices, $serializer->serialize($inspectedDevices, 'json'));

            }

        }


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $jsonData['missing-devices'] = $missingInspectedDevices;

            return new JsonResponse($jsonData);
        }


    }

    /**
     * Deletes a InspectedDevice entity.
     *
     * @Route("/{id}/delete/{device}", name="fireinspection_delete_device")
     * @Method("DELETE")
     */
    public function deleteDeviceAction(Request $request, FireInspection $fireInspection, $device)
    {

        $em = $this->getDoctrine()->getManager();

        $inspectedDevice = $em->getRepository('MicroBundle:InspectedDevice')->findOneById($device);

        $fireInspection = $inspectedDevice->getFireInspection();
        $fireInspection->removeInspectedDevice($inspectedDevice);

        $em->remove($inspectedDevice);
        $em->flush();


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $success = ($em->getRepository('MicroBundle:InspectedDevice')->findOneById($device)) ? true : false;
            $jsonData['success'] = $success;

            return new JsonResponse($jsonData);
        }
    }


    /**
     * Deletes a fireInspection entity.
     *
     * @Route("/{id}", name="fireinspection_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, FireInspection $fireInspection)
    {
        $form = $this->createDeleteForm($fireInspection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($fireInspection);
            $em->flush();
        }

        return $this->redirectToRoute('fireinspection_index');
    }

    /**
     * Creates a form to delete a fireInspection entity.
     *
     * @param FireInspection $fireInspection The fireInspection entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(FireInspection $fireInspection)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('fireinspection_delete', array('id' => $fireInspection->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }


}
