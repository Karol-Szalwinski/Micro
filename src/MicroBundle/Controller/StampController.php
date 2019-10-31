<?php

namespace MicroBundle\Controller;

use MicroBundle\Entity\Stamp;
use MicroBundle\Services\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

/**
 * Stamp controller.
 *
 * @Route("stamp")
 */
class StampController extends Controller
{
    /**
     * Lists all stamp entities.
     *
     * @Route("/", name="stamp_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $stamps = $em->getRepository('MicroBundle:Stamp')->findAll();

        return $this->render('stamp/index.html.twig', array(
            'stamps' => $stamps,
        ));
    }

    /**
     * Creates a new stamp entity.
     *
     * @Route("/new/{type}", name="stamp_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param $type
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, FileUploader $fileUploader, $type)
    {
        $stamp = new Stamp();
        if($type === 'main') {
            $stamp->setMain(true);
            $form = $this->createForm('MicroBundle\Form\StampMainType', $stamp);
        }
        else {
            $form = $this->createForm('MicroBundle\Form\StampInspectorType', $stamp);
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $stampFile = $form['image']->getData();
            if ($stampFile) {
                $stampFileName = $fileUploader->upload($stampFile);
                $stamp->setImage($stampFileName);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($stamp);
            $em->flush();

            return $this->redirectToRoute('stamp_index', array('id' => $stamp->getId()));
        }

        return $this->render('stamp/new.html.twig', array(
            'stamp' => $stamp,
            'type' => $type,
            'form' => $form->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing stamp entity.
     *
     * @Route("/{id}/edit", name="stamp_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Stamp $stamp, FileUploader $fileUploader)
    {
        $stampFile = $this->getParameter('target_directory') . '/images' . $stamp->getImage();
        if(is_file($stampFile)) {
            $stamp->setImage(new File($stampFile));
        }
        if($stamp->getMain()) {
            $editForm = $this->createForm('MicroBundle\Form\StampMainType', $stamp);
            $type = 'main';
        }
        else {
            $editForm = $this->createForm('MicroBundle\Form\StampInspectorType', $stamp);
            $type = 'inspector';
        }
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $stampFile = $editForm['image']->getData();
            if ($stampFile) {
                $stampFileName = $fileUploader->upload($stampFile);
                $stamp->setImage($stampFileName);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('stamp_index', array('id' => $stamp->getId()));
        }

        return $this->render('stamp/edit.html.twig', array(
            'stamp' => $stamp,
            'type' => $type,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Set stamp as deleted
     *
     * @Route("/{id}", name="stamp_delete")
     * @Method("POST")
     * @param Stamp $stamp
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Stamp $stamp)
    {

        $stamp->setDeleted();
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('stamp_index');
    }

}
