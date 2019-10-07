<?php

namespace MicroBundle\Controller;

use MicroBundle\Entity\Offert;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Offert controller.
 *
 * @Route("offert")
 */
class OffertController extends Controller
{
    /**
     * Lists all offert entities.
     *
     * @Route("/", name="offert_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $offerts = $em->getRepository('MicroBundle:Offert')->findAll();

        return $this->render('offert/index.html.twig', array(
            'offerts' => $offerts,
        ));
    }

    /**
     * Creates a new offert entity.
     *
     * @Route("/new", name="offert_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $offert = new Offert();
        $form = $this->createForm('MicroBundle\Form\OffertType', $offert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($offert);
            $em->flush();

            return $this->redirectToRoute('offert_show', array('id' => $offert->getId()));
        }

        return $this->render('offert/new.html.twig', array(
            'offert' => $offert,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a offert entity.
     *
     * @Route("/{id}", name="offert_show")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Offert $offert
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request,Offert $offert)
    {
        $form = $this->createForm('MicroBundle\Form\OffertType', $offert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('offert_index');
        }

        return $this->render('offert/show.html.twig', array(
            'offert' => $offert,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing offert entity.
     *
     * @Route("/{id}/edit", name="offert_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Offert $offert)
    {
        $deleteForm = $this->createDeleteForm($offert);
        $editForm = $this->createForm('MicroBundle\Form\OffertType', $offert);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('offert_edit', array('id' => $offert->getId()));
        }

        return $this->render('offert/edit.html.twig', array(
            'offert' => $offert,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a offert entity.
     *
     * @Route("/{id}", name="offert_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Offert $offert)
    {
        $form = $this->createDeleteForm($offert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($offert);
            $em->flush();
        }

        return $this->redirectToRoute('offert_index');
    }

    /**
     * Creates a form to delete a offert entity.
     *
     * @param Offert $offert The offert entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Offert $offert)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('offert_delete', array('id' => $offert->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
