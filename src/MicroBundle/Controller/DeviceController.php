<?php

namespace MicroBundle\Controller;

use MicroBundle\Entity\Device;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Devicename controller.
 *
 * @Route("device")
 */
class DeviceController extends Controller
{
    /**
     * Lists all deviceName entities.
     *
     * @Route("/", name="device_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $deviceNames = $em->getRepository('MicroBundle:Device')->findAll();

        return $this->render('device/index.html.twig', array('deviceNames' => $deviceNames,));
    }

    /**
     * Creates a new deviceName entity.
     *
     * @Route("/new", name="device_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $deviceName = new Device();
        $form = $this->createForm('MicroBundle\Form\DeviceType', $deviceName);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($deviceName);
            $em->flush();

            return $this->redirectToRoute('device_show', array('id' => $deviceName->getId()));
        }

        return $this->render('device/new.html.twig', array('deviceName' => $deviceName, 'form' => $form->createView(),));
    }

    /**
     * Finds and displays a deviceName entity.
     *
     * @Route("/{id}", name="device_show")
     * @Method("GET")
     */
    public function showAction(Device $deviceName)
    {

        return $this->render('device/show.html.twig', array('deviceName' => $deviceName));
    }

    /**
     * Displays a form to edit an existing deviceName entity.
     *
     * @Route("/{id}/edit", name="device_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Device $deviceName)
    {
        $editForm = $this->createForm('MicroBundle\Form\DeviceType', $deviceName);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('device_edit', array('id' => $deviceName->getId()));
        }

        return $this->render('device/edit.html.twig', array('deviceName' => $deviceName, 'edit_form' => $editForm->createView()));
    }

    /**
     * Set device as deleted
     *
     * @Route("/delete/{id}", name="device_delete")
     * @Method("POST")
     * @param Device $device
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Device $device)
    {

        $device->setDeleted();
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('device_index');
    }

}
