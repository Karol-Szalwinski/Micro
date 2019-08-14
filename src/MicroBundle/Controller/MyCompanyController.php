<?php

namespace MicroBundle\Controller;

use MicroBundle\Entity\MyCompany;
use MicroBundle\Repository\MyCompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("", name="mycompany_show")
     * @Method("GET")
     */
    public function showAction(MyCompanyRepository $repository)
    {
        $myCompany = $repository->returnDefaultMyCompany();
        return $this->render('mycompany/show.html.twig', array(
            'myCompany' => $myCompany,
        ));
    }

    /**
     * Displays a form to edit an existing myCompany entity.
     *
     * @Route("/edit", name="mycompany_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, MyCompanyRepository $repository)
    {
        $myCompany = $repository->returnDefaultMyCompany();

        $editForm = $this->createForm('MicroBundle\Form\MyCompanyType', $myCompany);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mycompany_show');
        }

        return $this->render('mycompany/edit.html.twig', array(
            'edit_form' => $editForm->createView(),
        ));
    }





}
