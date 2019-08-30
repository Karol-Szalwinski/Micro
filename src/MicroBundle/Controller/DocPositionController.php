<?php

namespace MicroBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use MicroBundle\Entity\Document;
use MicroBundle\Entity\DocDevice;
use MicroBundle\Entity\DocPosition;
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
 * @Route("doc-position")
 */
class DocPositionController extends Controller
{


    /**
     * Update DocDevice
     * @Method({"GET", "POST"})
     * @Route("/update/{jsondevice}", name="doc_position_update")
     * @param Request $request
     * @param $jsondevice
     * @return JsonResponse
     */
    public function updateAction(Request $request, $jsondevice)
    {
        $em = $this->getDoctrine()->getManager();

        $jsonDevice = json_decode($jsondevice);

        $docPosition = $em->getRepository('MicroBundle:DocPosition')->findOneBy(['id' => $jsonDevice->{'id'}]);

        if (array_key_exists('name', $jsonDevice)) {

            $docPosition->setName($jsonDevice->{'name'});
        }
        if (array_key_exists('test', $jsonDevice)) {

            $docPosition->setTest($jsonDevice->{'test'});
        }
        if (array_key_exists('comment', $jsonDevice)) {

            $docPosition->setComment($jsonDevice->{'comment'});
        }

        $em->flush();


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $normalizer = new ObjectNormalizer();
            $normalizer->setIgnoredAttributes(['document']);
            $encoder = new JsonEncoder();

            $serializer = new Serializer([$normalizer], [$encoder]);
            $serializeDevice = $serializer->serialize($docPosition, 'json');


            $jsonData['doc_position'] = $serializeDevice;


            return new JsonResponse($jsonData);
        }
    }

    /**
     * Creates a new testPosition.
     *
     * @Route("/add/{documentId}", name="doc_position_add")
     * @Method({"POST"})
     * @param Request $request
     * @param $documentId
     * @return JsonResponse
     */
    public function testPositionAddAction(Request $request, $documentId)
    {
        $em = $this->getDoctrine()->getManager();
        $document= $em->getRepository("MicroBundle\Entity\Document")->FindOneBy(['id'=>$documentId]);
        $docPosition = New DocPosition();
        $docPosition->setDocument($document);
        $document->addDocPosition($docPosition);

        $em->persist($docPosition);
        $em->flush();
        $id = $docPosition->getId();

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $jsonData['id'] = $id;

            return new JsonResponse($jsonData);
        }

    }

    /**
     * Delete docPosition
     *
     * @Route("/delete/{docPositionId}", name="doc_position_delete")
     * @param Request $request
     * @param $docPositionId
     * @return JsonResponse
     */
    public function deleteTestPositionAction(Request $request, $docPositionId)
    {
        $em = $this->getDoctrine()->getManager();
        $docPosition= $em->getRepository("MicroBundle\Entity\DocPosition")->FindOneBy(['id'=>$docPositionId]);
        $docPosition->getDocument()->removeDocPosition($docPosition);
        $em->remove($docPosition);

        $em->flush();


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $jsonData['id'] = $docPositionId;

            return new JsonResponse($jsonData);
        }

    }



}
