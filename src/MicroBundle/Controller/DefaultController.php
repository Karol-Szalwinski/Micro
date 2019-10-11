<?php

namespace MicroBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $mainCategories = $em->getRepository('MicroBundle:Category')->findBy(['parent' => null]);

        $menuForm = $this->createForm('MicroBundle\Form\MenuType');

        $menuForm->handleRequest($request);
        if ($menuForm->isSubmitted()) {
            $menu =  $menuForm["type"]->getData();
//            dump($menu);die();
            $menuForm->getData();
            if ($menu == 'admin' || $menu == 'document' || $menu == 'product') {
                $this->get('session')->set('menu', $menu);
            }
            return $this->redirectToRoute('micro_default_index');
        }

        return $this->render('default/index.html.twig', array(
            'mainCategories' => $mainCategories,
            'menu_form' => $menuForm->createView(),
        ));
    }


    /**
     * @Route("/menu-counter")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxMenuCounterAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $clientsCount = $em->getRepository('MicroBundle:Client')->count([]);
        $buildingsCount = $em->getRepository('MicroBundle:Building')->count([]);
        $documentCount = $em->getRepository('MicroBundle:Document')->count([]);

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $jsonData['buildings'] = $buildingsCount;
            $jsonData['clients'] = $clientsCount;
            $jsonData['documents'] = $documentCount - 1; //except one default
            return new JsonResponse($jsonData);
        }


    }
}
