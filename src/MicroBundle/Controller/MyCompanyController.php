<?php

namespace MicroBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use MicroBundle\Services\FileUploader;

/**
 * Mycompany controller.
 *
 * @Route("mycompany")
 */
class MyCompanyController extends Controller
{

    /**
     * Finds and displays a myCompany entity.
     *
     * @Route("/", name="mycompany_show")
     * @Method("GET")
     */
    public function showAction()
    {
        $myCompany = $this->get('mycompany')->getOrCreateDefaultMyCompany();

        return $this->render('mycompany/show.html.twig', array('mycompany' => $myCompany,));
    }

    /**
     * Displays a form to edit an existing myCompany entity.
     *
     * @Route("/edit", name="mycompany_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        $serviceMyCompany = $this->get('mycompany');
        $myCompany = $serviceMyCompany->getOrCreateDefaultMyCompany();

        $editForm = $this->createForm('MicroBundle\Form\MyCompanyType', $myCompany);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $serviceMyCompany->updateMyCompany($myCompany);

            return $this->redirectToRoute('mycompany_show');
        }

        return $this->render('mycompany/edit.html.twig', array('edit_form' => $editForm->createView(),));
    }


}
