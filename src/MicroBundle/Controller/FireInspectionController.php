<?php

namespace MicroBundle\Controller;

use MicroBundle\Entity\Building;
use MicroBundle\Entity\FireInspection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

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
        $deleteForm = $this->createDeleteForm($fireInspection);
        $editForm = $this->createForm('MicroBundle\Form\FireInspectionType', $fireInspection);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('fireinspection_edit', array('id' => $fireInspection->getId()));
        }

        return $this->render('fireinspection/edit.html.twig', array(
            'fireInspection' => $fireInspection,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
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
            ->getForm()
        ;
    }
}
