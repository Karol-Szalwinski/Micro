<?php

namespace MicroBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use MicroBundle\Entity\FireInspection;
use MicroBundle\Entity\InspectedDevice;
use MicroBundle\Entity\TestPosition;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


/**
 * Test Position controller.
 *
 * @Route("testposition")
 */
class TestPositionController extends Controller
{


    /**
     * Change pool in Test position
     * @Method({"POST"})
     * @Route("/{htmlid}/update/{value}")
     * @param $request
     * @param String $htmlid
     * @param String $value
     * @return JsonResponse
     */
    public function updateTestPositionAction(Request $request, $htmlid, $value)
    {
        $type = substr($htmlid, 0, 6);
        $id = substr($htmlid, 6);
        $value = ($value == "null") ? "" : $value;

        $em = $this->getDoctrine()->getManager();
        $testPosition = $em->getRepository('MicroBundle:TestPosition')->findOneById($id);

        $responseText = null;
        switch ($type) {
            case ('namei-'):

                $testPosition->setName($value);
                $responseText = $testPosition->getName();

                break;
            case ('testc-'):

                $testPosition->setTest(!$testPosition->getTest());
                $responseText = $testPosition->getTest();

                break;
            case ('commi-'):

                $testPosition->setComment($value);
                $responseText = $testPosition->getComment();
        }


        $em->flush();


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $jsonData['id'] = $id;
            $jsonData['type'] = $type;
            $jsonData['text'] = $responseText;


            return new JsonResponse($jsonData);
        }
    }

    /**
     * Creates a new testPosition.
     *
     * @Route("/add/{fireInspectionId}", name="test_position_add")
     * @Method({"POST"})
     * @param Request $request
     * @param $fireInspectionId
     * @return JsonResponse
     */
    public function testPositionAddAction(Request $request, $fireInspectionId)
    {
        $em = $this->getDoctrine()->getManager();
        $fireInspection= $em->getRepository("MicroBundle\Entity\FireInspection")->FindOneBy(['id'=>$fireInspectionId]);
        $testPosition = New TestPosition();
        $testPosition->setFireInspection($fireInspection);
        $fireInspection->addTestPosition($testPosition);

        $em->persist($testPosition);
        $em->flush();
        $id = $testPosition->getId();

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $jsonData['id'] = $id;

            return new JsonResponse($jsonData);
        }

    }

    /**
     * Delete testPosition
     *
     * @Route("/delete/{testPositionId}", name="test_position_delete")
     * @param Request $request
     * @param $testPositionId
     * @return JsonResponse
     */
    public function deleteTestPositionAction(Request $request, $testPositionId)
    {
        $em = $this->getDoctrine()->getManager();
        $id = substr($testPositionId, 6);
        $testPosition= $em->getRepository("MicroBundle\Entity\TestPosition")->FindOneBy(['id'=>$id]);
        $testPosition->getFireInspection()->removeTestPosition($testPosition);
        $em->remove($testPosition);

        $em->flush();


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $jsonData['id'] = $id;

            return new JsonResponse($jsonData);
        }

    }



}
