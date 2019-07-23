<?php

namespace MicroBundle\Controller;

use MicroBundle\Entity\Building;
use MicroBundle\Entity\DocumentInspector;
use MicroBundle\Entity\FireInspection;
use MicroBundle\Entity\InspectedDevice;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * Default Fireinspection controller.
 *
 * @Route("deffireinspection")
 */
class DefFireInspectionController extends Controller
{


    /**
     * Finds and displays a fireInspection entity.
     *
     * @Route("/show", name="def_fireinspection_show")
     * @Method("GET")
     */
    public function showAction()
    {
        $fireInspection = $this->getDoctrine()->getManager()->getRepository('MicroBundle:FireInspection')
            ->findOneBy(['id' => 1 ]);

        return $this->render('deffireinspection/show.html.twig', array(
            'fireInspection' => $fireInspection,
        ));
    }

    /**
     * Displays a form to edit default fireInspection entity.
     *
     * @Route("/edit", name="def_fireinspection_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $fireInspection = $em->getRepository('MicroBundle:FireInspection')
            ->findOneBy(['id' => 1 ]);
        $editForm = $this->createForm('MicroBundle\Form\DefFireInspectionType', $fireInspection);

//        dump($fireInspection); die();
        $editForm->handleRequest($request);


        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em->flush();
            return $this->redirectToRoute('def_fireinspection_show');
        }

        return $this->render('deffireinspection/edit.html.twig', array(
            'fireInspection' => $fireInspection,
            'edit_form' => $editForm->createView()
        ));

    }

    /**
     * Displays a form to edit an existing fireInspection entity summary.
     *
     * @Route("/editsum", name="def_fireinspection_edit_summary")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editSumAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $fireInspection = $em->getRepository('MicroBundle:FireInspection')
            ->findOneBy(['id' => 1 ]);
        $editForm = $this->createForm('MicroBundle\Form\FireInspectionSummaryType', $fireInspection);

        $editForm->handleRequest($request);



        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em->flush();

            return $this->redirectToRoute('def_fireinspection_show');
        }

        return $this->render('deffireinspection/editsum.html.twig', array(
            'fireInspection' => $fireInspection,
            'edit_form' => $editForm->createView()
        ));
    }




}
