<?php

namespace MicroBundle\Controller;

use MicroBundle\Entity\Inspector;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Inspector controller.
 *
 * @Route("inspector")
 */
class InspectorController extends Controller
{
    /**
     * Lists all inspector entities.
     *
     * @Route("/", name="inspector_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $inspectors = $em->getRepository('MicroBundle:Inspector')->findAll();

        return $this->render('inspector/index.html.twig', array(
            'inspectors' => $inspectors,
        ));
    }

    /**
     * Creates a new inspector entity.
     *
     * @Route("/new", name="inspector_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $inspector = new Inspector();
        $form = $this->createForm('MicroBundle\Form\InspectorType', $inspector);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inspector);
            $em->flush();

            return $this->redirectToRoute('inspector_show', array('id' => $inspector->getId()));
        }

        return $this->render('inspector/new.html.twig', array(
            'inspector' => $inspector,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a inspector entity.
     *
     * @Route("/{id}", name="inspector_show")
     * @Method("GET")
     */
    public function showAction(Inspector $inspector)
    {
        $deleteForm = $this->createDeleteForm($inspector);

        return $this->render('inspector/show.html.twig', array(
            'inspector' => $inspector,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing inspector entity.
     *
     * @Route("/{id}/edit", name="inspector_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Inspector $inspector)
    {
        $deleteForm = $this->createDeleteForm($inspector);
        $editForm = $this->createForm('MicroBundle\Form\InspectorType', $inspector);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('inspector_show', array('id' => $inspector->getId()));
        }

        return $this->render('inspector/edit.html.twig', array(
            'inspector' => $inspector,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a inspector entity.
     *
     * @Route("/{id}", name="inspector_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Inspector $inspector)
    {
        $form = $this->createDeleteForm($inspector);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($inspector);
            $em->flush();
        }

        return $this->redirectToRoute('inspector_index');
    }

    /**
     * Creates a form to delete a inspector entity.
     *
     * @param Inspector $inspector The inspector entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Inspector $inspector)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('inspector_delete', array('id' => $inspector->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
