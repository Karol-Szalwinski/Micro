<?php

namespace MicroBundle\Controller;

use MicroBundle\Entity\LoopDev;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Loopdev controller.
 *
 * @Route("loopdev")
 */
class LoopDevController extends Controller
{
    /**
     * Lists all loopDev entities.
     *
     * @Route("/", name="loopdev_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $loopDevs = $em->getRepository('MicroBundle:LoopDev')->findAll();

        return $this->render('loopdev/index.html.twig', array(
            'loopDevs' => $loopDevs,
        ));
    }

    /**
     * Creates a new loopDev entity.
     *
     * @Route("/new", name="loopdev_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $loopDev = new Loopdev();
        $form = $this->createForm('MicroBundle\Form\LoopDevType', $loopDev);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($loopDev);
            $em->flush();

            return $this->redirectToRoute('loopdev_show', array('id' => $loopDev->getId()));
        }

        return $this->render('loopdev/new.html.twig', array(
            'loopDev' => $loopDev,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a loopDev entity.
     *
     * @Route("/{id}", name="loopdev_show")
     * @Method("GET")
     */
    public function showAction(LoopDev $loopDev)
    {
        $deleteForm = $this->createDeleteForm($loopDev);

        return $this->render('loopdev/show.html.twig', array(
            'loopDev' => $loopDev,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing loopDev entity.
     *
     * @Route("/{id}/edit", name="loopdev_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, LoopDev $loopDev)
    {
        $deleteForm = $this->createDeleteForm($loopDev);
        $editForm = $this->createForm('MicroBundle\Form\LoopDevType', $loopDev);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('loopdev_edit', array('id' => $loopDev->getId()));
        }

        return $this->render('loopdev/edit.html.twig', array(
            'loopDev' => $loopDev,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Add or Update Fire Protection Device
     * @Method({"GET", "POST"})
     * @Route("/delete-loopdev/{id}")
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function deleteLoopDevAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $loopDev = $em->getRepository('MicroBundle:LoopDev')->findOneBy(['id' => $id]);
        $success = false;
        $errorLast = !$loopDev->isLastNotDeleted();
        $errorEmpty = !$loopDev->hasNotUndeletedDevices();
        if(!$errorLast && !$errorEmpty) {
            $loopDev->setDel(true);
            $em->flush();
            $success = true;
        };

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $jsonData['id'] = $id;
            $jsonData['success'] = $success;
            $jsonData['error-last'] = $errorLast;
            $jsonData['error-empty'] = $errorEmpty;

            return new JsonResponse($jsonData);
        }
    }
}
