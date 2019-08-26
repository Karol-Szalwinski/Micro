<?php

namespace MicroBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use MicroBundle\Entity\Document;
use MicroBundle\Entity\DocDevice;
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
 * Inspecteddevice controller.
 *
 * @Route("doc-device")
 */
class DocDeviceController extends Controller
{

    /**
     * Update DocDevice
     * @Method({"GET", "POST"})
     * @Route("/update/{jsondevice}", name="doc_device_update")
     * @param Request $request
     * @param $jsondevice
     * @return JsonResponse
     */
    public function updateAction(Request $request, $jsondevice)
    {
        $em = $this->getDoctrine()->getManager();

        $jsonDevice = json_decode($jsondevice);

        $docDevice = $em->getRepository('MicroBundle:DocDevice')->findOneBy(['id' => $jsonDevice->{'id'}]);
        $buildDevice = $docDevice->getBuildDevice();

        if (array_key_exists('number', $jsonDevice)) {
            $docDevice->setNumber($jsonDevice->{'number'});
        }
        if (array_key_exists('shortname', $jsonDevice)) {
            if($buildDevice->getShortname() == null) {
                $buildDevice->setShortname($jsonDevice->{'shortname'});
                $deviceName = $em->getRepository('MicroBundle:Device')->findOneBy(['shortname' => $jsonDevice->{'shortname'}]);
                $name = ($deviceName) ? $deviceName->getName() : "";
                $buildDevice->setName($name);
            }

            $docDevice->setShortname($jsonDevice->{'shortname'});
        }
        if (array_key_exists('status', $jsonDevice)) {

            $docDevice->setStatus($jsonDevice->{'status'});
        }
        if (array_key_exists('test', $jsonDevice)) {

            $docDevice->setTest($jsonDevice->{'test'});
        }
        if (array_key_exists('comment', $jsonDevice)) {

            $docDevice->setComment($jsonDevice->{'comment'});
        }
        if (array_key_exists('visible', $jsonDevice)) {

            $docDevice->toggleVisible();
        }

        $em->flush();


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $normalizer = new ObjectNormalizer();
            $normalizer->setIgnoredAttributes(['document', 'buildDevice']);
            $encoder = new JsonEncoder();

            $serializer = new Serializer([$normalizer], [$encoder]);
            $serializeDevice = $serializer->serialize($docDevice, 'json');


            $jsonData['doc_device'] = $serializeDevice;


            return new JsonResponse($jsonData);
        }
    }

}
