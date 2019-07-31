<?php

namespace MicroBundle\Controller;

use MicroBundle\Entity\Building;
use MicroBundle\Entity\DocumentInspector;
use MicroBundle\Entity\FireInspection;
use MicroBundle\Entity\InspectedDevice;
use MicroBundle\Entity\Inspector;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


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

        $fireInspections = $em->getRepository('MicroBundle:FireInspection')->findAllExceptId(1);

        return $this->render('fireinspection/index.html.twig', array('fireInspections' => $fireInspections,));
    }

    /**
     * Creates a new fireInspection entity.
     *
     * @Route("/new/{building}", name="fireinspection_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Building $building, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $defaultFireInspection = $em->getRepository('MicroBundle:FireInspection')->findOneBy(['id' => 1]);
        $fireInspection = clone $defaultFireInspection;
        $fireInspection->setDeviceShortlistPosition($building->getDeviceShortlistPosition());
        $fireInspection->setInspectionDate(new \DateTime());
        $fireInspection->setNextInspectionDate(new \DateTime('now + 6 month'));


        $form = $this->createForm('MicroBundle\Form\FireInspectionType', $fireInspection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $building->addFireInspection($fireInspection);
            $fireInspection->setBuilding($building);
            //add inspectors to document
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
            //add test position to document
            foreach ($defaultFireInspection->getTestPositions() as $defTestPosition) {
                $testPosition = clone $defTestPosition;
                $testPosition->setFireInspection($fireInspection);
                $fireInspection->addTestPosition($testPosition);
                $em->persist($testPosition);
            }

            //add InspectedDevice to document
            $loopDevs = $building->getLoopDevs();
            foreach ($loopDevs as $loopDev) {
                foreach ($loopDev->getFireProtectionDevices() as $device) {
                    $inspectedDevices = new InspectedDevice();
                    $inspectedDevices->setNumber($device->getNumber());
                    $inspectedDevices->setShortname($device->getShortname());

                    $inspectedDevices->setFireProtectionDevice($device);
                    $device->addInspectedDevice($inspectedDevices);

                    $inspectedDevices->setFireInspection($fireInspection);
                    $fireInspection->addInspectedDevice($inspectedDevices);

                    $em->persist($inspectedDevices);
                }

            }





            $em->persist($fireInspection);
            $em->flush();

            return $this->redirectToRoute('fireinspection_show', array('id' => $fireInspection->getId()));
        }

        return $this->render('fireinspection/new.html.twig', array('fireInspection' => $fireInspection, 'building' => $building, 'form' => $form->createView(),));
    }

    /**
     * Finds and displays a fireInspection entity.
     *
     * @Route("/{id}", name="fireinspection_show")
     * @Method("GET")
     */
    public function showAction(FireInspection $fireInspection)
    {
        $this->container->get('micro')->updateLastServiceDateFireInspection($fireInspection);
        $em = $this->getDoctrine()->getManager();
        $loopDevs=[];
        foreach ( $fireInspection->getBuilding()->getLoopDevs() as $loop) {;

            $loopDev = $em->getRepository('MicroBundle:FireInspection')->findDevicesByFireInspection($fireInspection->getId(), $loop->getId());
            $loopDevs[] = $loopDev;

        }


        return $this->render('fireinspection/show.html.twig',
            array('fireInspection' => $fireInspection,
                'loopDevs' => $loopDevs,
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
        $em = $this->getDoctrine()->getManager();

        foreach ($fireInspection->getDocumentInspectors() as $inspector) {
            if ($inspector->getPrototype()) {
                $prototypeId = $inspector->getPrototype();
                $prototype = $em->getRepository('MicroBundle:Inspector')->findOneBy(['id' => $prototypeId]);
                 if ($prototype instanceof Inspector) {
                     $fireInspection->addTempInspector($prototype);
                 }
                }
        }

        $editForm = $this->createForm('MicroBundle\Form\FireInspectionType', $fireInspection);

        $editForm->handleRequest($request);


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
                $docInspector->setPrototype($inspector->getId());
                $fireInspection->addDocumentInspector($docInspector);
                $fireInspection->removeTempInspector($inspector);
            }

            $em->flush();
            return $this->redirectToRoute('fireinspection_show', array('id' => $fireInspection->getId()));
        }

        return $this->render('fireinspection/edit.html.twig', array('fireInspection' => $fireInspection, 'edit_form' => $editForm->createView(),));

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

        return $this->render('fireinspection/editsum.html.twig', array('fireInspection' => $fireInspection, 'edit_form' => $editForm->createView(),));
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
        return $this->createFormBuilder()->setAction($this->generateUrl('fireinspection_delete', array('id' => $fireInspection->getId())))->setMethod('DELETE')->getForm();
    }


}
