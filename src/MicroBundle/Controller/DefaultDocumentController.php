<?php

namespace MicroBundle\Controller;

use MicroBundle\Entity\Building;
use MicroBundle\Entity\DocumentInspector;
use MicroBundle\Entity\Document;
use MicroBundle\Entity\DocDevice;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * Default document controller.
 *
 * @Route("defaultdocument")
 */
class DefaultDocumentController extends Controller
{


    /**
     * Finds and displays a document entity.
     *
     * @Route("/show", name="def_document_show")
     * @Method("GET")
     */
    public function showAction()
    {
        $document = $this->getDoctrine()->getManager()->getRepository('MicroBundle:Document')
            ->findOneBy(['id' => 1 ]);

        return $this->render('defaultdocument/show.html.twig', array(
            'document' => $document,
        ));
    }

    /**
     * Displays a form to edit default document entity.
     *
     * @Route("/edit", name="def_document_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $document = $em->getRepository('MicroBundle:Document')
            ->findOneBy(['id' => 1 ]);
        $editForm = $this->createForm('MicroBundle\Form\DefdocumentType', $document);

//        dump($document); die();
        $editForm->handleRequest($request);


        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em->flush();
            return $this->redirectToRoute('def_document_show');
        }

        return $this->render('defaultdocument/edit.html.twig', array(
            'document' => $document,
            'edit_form' => $editForm->createView()
        ));

    }

    /**
     * Displays a form to edit an existing document entity summary.
     *
     * @Route("/editsum", name="def_document_edit_summary")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editSumAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $document = $em->getRepository('MicroBundle:Document')
            ->findOneBy(['id' => 1 ]);
        $editForm = $this->createForm('MicroBundle\Form\DocumentSummaryType', $document);

        $editForm->handleRequest($request);



        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em->flush();

            return $this->redirectToRoute('def_document_show');
        }

        return $this->render('defaultdocument/editsum.html.twig', array(
            'document' => $document,
            'edit_form' => $editForm->createView()
        ));
    }




}
