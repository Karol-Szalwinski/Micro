<?php

namespace MicroBundle\Controller;

use MicroBundle\Entity\DeviceName;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Devicename controller.
 *
 * @Route("devicename")
 */
class DeviceNameController extends Controller
{
    /**
     * Lists all deviceName entities.
     *
     * @Route("/", name="devicename_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $deviceNames = $em->getRepository('MicroBundle:DeviceName')->findAll();

        return $this->render('devicename/index.html.twig', array(
            'deviceNames' => $deviceNames,
        ));
    }

    /**
     * Creates a new deviceName entity.
     *
     * @Route("/new", name="devicename_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $deviceName = new Devicename();
        $form = $this->createForm('MicroBundle\Form\DeviceNameType', $deviceName);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($deviceName);
            $em->flush();

            return $this->redirectToRoute('devicename_show', array('id' => $deviceName->getId()));
        }

        return $this->render('devicename/new.html.twig', array(
            'deviceName' => $deviceName,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a deviceName entity.
     *
     * @Route("/{id}", name="devicename_show")
     * @Method("GET")
     */
    public function showAction(DeviceName $deviceName)
    {
        $deleteForm = $this->createDeleteForm($deviceName);

        return $this->render('devicename/show.html.twig', array(
            'deviceName' => $deviceName,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing deviceName entity.
     *
     * @Route("/{id}/edit", name="devicename_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, DeviceName $deviceName)
    {
        $deleteForm = $this->createDeleteForm($deviceName);
        $editForm = $this->createForm('MicroBundle\Form\DeviceNameType', $deviceName);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('devicename_edit', array('id' => $deviceName->getId()));
        }

        return $this->render('devicename/edit.html.twig', array(
            'deviceName' => $deviceName,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a deviceName entity.
     *
     * @Route("/{id}", name="devicename_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, DeviceName $deviceName)
    {
        $form = $this->createDeleteForm($deviceName);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($deviceName);
            $em->flush();
        }

        return $this->redirectToRoute('devicename_index');
    }

    /**
     * Creates a form to delete a deviceName entity.
     *
     * @param DeviceName $deviceName The deviceName entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(DeviceName $deviceName)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('devicename_delete', array('id' => $deviceName->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
