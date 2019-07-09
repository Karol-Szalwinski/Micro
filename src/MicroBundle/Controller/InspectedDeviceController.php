<?php

namespace MicroBundle\Controller;

use MicroBundle\Entity\InspectedDevice;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Inspecteddevice controller.
 *
 * @Route("inspecteddevice")
 */
class InspectedDeviceController extends Controller
{
    /**
     * Lists all inspectedDevice entities.
     *
     * @Route("/", name="inspecteddevice_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $inspectedDevices = $em->getRepository('MicroBundle:InspectedDevice')->findAll();

        return $this->render('inspecteddevice/index.html.twig', array(
            'inspectedDevices' => $inspectedDevices,
        ));
    }

    /**
     * Creates a new inspectedDevice entity.
     *
     * @Route("/new", name="inspecteddevice_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $inspectedDevice = new Inspecteddevice();
        $form = $this->createForm('MicroBundle\Form\InspectedDeviceType', $inspectedDevice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inspectedDevice);
            $em->flush();

            return $this->redirectToRoute('inspecteddevice_show', array('id' => $inspectedDevice->getId()));
        }

        return $this->render('inspecteddevice/new.html.twig', array(
            'inspectedDevice' => $inspectedDevice,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a inspectedDevice entity.
     *
     * @Route("/{id}", name="inspecteddevice_show")
     * @Method("GET")
     */
    public function showAction(InspectedDevice $inspectedDevice)
    {
        $deleteForm = $this->createDeleteForm($inspectedDevice);

        return $this->render('inspecteddevice/show.html.twig', array(
            'inspectedDevice' => $inspectedDevice,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing inspectedDevice entity.
     *
     * @Route("/{id}/edit", name="inspecteddevice_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, InspectedDevice $inspectedDevice)
    {
        $deleteForm = $this->createDeleteForm($inspectedDevice);
        $editForm = $this->createForm('MicroBundle\Form\InspectedDeviceType', $inspectedDevice);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('inspecteddevice_edit', array('id' => $inspectedDevice->getId()));
        }

        return $this->render('inspecteddevice/edit.html.twig', array(
            'inspectedDevice' => $inspectedDevice,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/{id}/changestatus/{type}")
     */
    public function changeStatusAction(Request $request, $id, String $type)
    {
        $em = $this->getDoctrine()->getManager();
        $inspectedDevice = $em->getRepository('MicroBundle:InspectedDevice')->findOneById($id);
        if ($type == 's') {


            if ($inspectedDevice->getStatus() == true) {
                $inspectedDevice->setStatus(false);
            } else {
                $inspectedDevice->setStatus(true);
            }
        }
        if ($type == 't') {

            if ($inspectedDevice->getTest() == true) {
                $inspectedDevice->setTest(false);
            } else {
                $inspectedDevice->setTest(true);
            }
        }

        $em->flush();


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $jsonData['status'] = $inspectedDevice->getStatus();
            $jsonData['test'] = $inspectedDevice->getTest();
            $jsonData['type'] = $type;

            $jsonData['id'] = $inspectedDevice->getId();

            return new JsonResponse($jsonData);
//        } else {
//            return $this->render('student/ajax.html.twig');
        }
    }

    /**
     * @Route("/{id}/changecomment/{comm}", name="inspected_device_change_comment")
     */
    public function changeCommentAction(Request $request, $id, $comm)
    {
        $em = $this->getDoctrine()->getManager();
        $inspectedDevice = $em->getRepository('MicroBundle:InspectedDevice')->findOneById($id);
        $comment = ($comm == "null") ? "" : $comm;
        $inspectedDevice->setComment($comment);

        $em->flush();


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $jsonData['comm'] = $inspectedDevice->getComment();
            $jsonData['id'] = $inspectedDevice->getId();

            return new JsonResponse($jsonData);
        }
    }

    /**
     * Deletes a inspectedDevice entity.
     *
     * @Route("/{id}", name="inspecteddevice_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, InspectedDevice $inspectedDevice)
    {
        $form = $this->createDeleteForm($inspectedDevice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($inspectedDevice);
            $em->flush();
        }

        return $this->redirectToRoute('inspecteddevice_index');
    }

    /**
     * Creates a form to delete a inspectedDevice entity.
     *
     * @param InspectedDevice $inspectedDevice The inspectedDevice entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(InspectedDevice $inspectedDevice)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('inspecteddevice_delete', array('id' => $inspectedDevice->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
