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

        return $this->render('inspector/show.html.twig', array(
            'inspector' => $inspector,
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
        $editForm = $this->createForm('MicroBundle\Form\InspectorType', $inspector);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('inspector_show', array('id' => $inspector->getId()));
        }

        return $this->render('inspector/edit.html.twig', array(
            'inspector' => $inspector,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Set inspector as deleted
     *
     * @Route("/delete/{id}", name="inspector_delete")
     * @Method("POST")
     * @param Inspector $inspector
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Inspector $inspector)
    {

        $inspector->setDeleted();
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('inspector_index');
    }

}
