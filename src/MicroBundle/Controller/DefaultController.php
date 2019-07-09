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
     */
    public function ajaxAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $clientsCount = $em->getRepository('MicroBundle:Client')->count([]);
        $buildingsCount = $em->getRepository('MicroBundle:Building')->count([]);
        $fireInspectionCount = $em->getRepository('MicroBundle:FireInspection')->count([]);


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $jsonData['buildings'] = $buildingsCount;
            $jsonData['clients'] = $clientsCount;
            $jsonData['fireInspections'] = $fireInspectionCount;
            return new JsonResponse($jsonData);
//        } else {
//            return $this->render('student/ajax.html.twig');
        }




    }
}
