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
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }


    /**
     * @Route("/ajax")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxAction(Request $request) {
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
