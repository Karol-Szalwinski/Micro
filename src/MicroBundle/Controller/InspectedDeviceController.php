<?php

namespace MicroBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use MicroBundle\Entity\FireInspection;
use MicroBundle\Entity\InspectedDevice;
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
 * @Route("inspdev")
 */
class InspectedDeviceController extends Controller
{


    /**
     * Change status or test in InspectedDevice
     * @Method({"GET", "POST"})
     * @Route("/{id}/changestatus/{type}")
     * @param Request $request
     * @param $id
     * @param $type
     * @return JsonResponse
     */
    public function changeStatusAction(Request $request, $id, $type)
    {
        $em = $this->getDoctrine()->getManager();
        $inspectedDevice = $em->getRepository('MicroBundle:InspectedDevice')->findOneById($id);
        if ($type == 's') {


            if ($inspectedDevice->getStatus() == true) {
                $inspectedDevice->setStatus(false);
            } else {
                $inspectedDevice->setStatus(true);
            }
        }
        if ($type == 't') {

            if ($inspectedDevice->getTest() == true) {
                $inspectedDevice->setTest(false);
            } else {
                $inspectedDevice->setTest(true);
            }
        }

        $em->flush();


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $jsonData['status'] = $inspectedDevice->getStatus();
            $jsonData['test'] = $inspectedDevice->getTest();
            $jsonData['type'] = $type;

            $jsonData['id'] = $inspectedDevice->getId();

            return new JsonResponse($jsonData);
        }
    }

    /**
     * Change comment Inspected Device
     *
     * @Route("/{id}/changecomment/{comm}", name="inspected_device_change_comment")
     * @param Request $request
     * @param $id
     * @param $comm
     * @return JsonResponse
     */
    public function changeCommentAction(Request $request, $id, $comm)
    {
        $em = $this->getDoctrine()->getManager();
        $inspectedDevice = $em->getRepository('MicroBundle:InspectedDevice')->findOneById($id);
        $comment = ($comm == "null") ? "" : $comm;
        $inspectedDevice->setComment($comment);

        $em->flush();


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $jsonData['comm'] = $inspectedDevice->getComment();
            $jsonData['id'] = $inspectedDevice->getId();

            return new JsonResponse($jsonData);
        }
    }

    /**
     * Create list of Inspected Devices.
     *
     * @Route("/{id}/loaddevices", name="fireinspection_load_devices")
     * @Method({"GET", "POST"})
     * @param FireInspection $fireInspection
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loadDevicesAction(FireInspection $fireInspection)
    {
        $em = $this->getDoctrine()->getManager();
        $fireInspection->setInspectedDevicesVisible();
        $fireProtectionDevices = $fireInspection->getBuilding()->getFireProtectionDevices();


        foreach ($fireProtectionDevices as $device) {
            $success = true;
            foreach ($fireInspection->getInspectedDevices() as $insDevice) {
                if ($device === $insDevice->getFireProtectionDevice()) {
                    $success = false;
                }
            }

            //If I find it , I create objects of inspectedDevices and push them into the array
            if ($success) {
                $inspectedDevices = new InspectedDevice();
                $inspectedDevices->setLoopNo($device->getLoopNo());
                $inspectedDevices->setNumber($device->getNumber());
                $inspectedDevices->setShortname($device->getShortname());

                $inspectedDevices->setFireProtectionDevice($device);
                $device->addInspectedDevice($inspectedDevices);

                $inspectedDevices->setFireInspection($fireInspection);
                $fireInspection->addInspectedDevice($inspectedDevices);

                $em->persist($inspectedDevices);


            }

        }
        $em->flush();


        return $this->redirectToRoute('fireinspection_show', array('id' => $fireInspection->getId()));


    }

    /**
     * @Route("/{id}/deldevices", name="fireinspection_del_devices")
     * @param FireInspection $fireInspection
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteDeviceAction(FireInspection $fireInspection)
    {
        if (!$fireInspection->getInspectedDevices()->isEmpty()) {

            $em = $this->getDoctrine()->getManager();

            foreach ($fireInspection->getInspectedDevices() as $device) {
                $fireInspection->removeInspectedDevice($device);
                $em->remove($device);
            }
            $em->flush();
        }
        return $this->redirectToRoute('fireinspection_show', array('id' => $fireInspection->getId()));
    }

    /**
     * @Route("/{id}/changevisible", name="inspecteddevice_change_visible")
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function inspectedDeviceChangeVisibleAction(Request $request, $id)
    {


        $em = $this->getDoctrine()->getManager();
        $inspectedDevice = $em->getRepository("MicroBundle:InspectedDevice")->findOneBy(["id" => $id]);


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $response = $inspectedDevice->changeVisible();

            $em->flush();

            $jsonData['response'] = $response;

            return new JsonResponse($jsonData);
        }
    }


    /**
     * Create list of Inspected Devices.
     *
     * @Route("/{fireInspection}/loadmissdevices", name="fireinspection_load_missed_devices")
     * @Method({"GET", "POST"})
     */
    public function loadMissedDevicesAction($fireInspection, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $fireInspection = $em->getRepository('MicroBundle:FireInspection')->findOneById($fireInspection);
        //all devices in building
        $fireProtectionDevices = $fireInspection->getBuilding()->getFireProtectionDevices();

        $missingInspectedDevices = [];

        //prepare serializer
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);


//looking for $fireProtectionDevices that have no equivalents in the document fireInspection
        foreach ($fireProtectionDevices as $device) {
            $success = true;
            foreach ($fireInspection->getInspectedDevices() as $insDevice) {
                if ($device == $insDevice->getFireProtectionDevice()) {
                    $success = false;
                }
            }

            //If I find it , I create objects of inspectedDevices and push them into the array
            if ($success) {
                $inspectedDevices = new InspectedDevice();
                $inspectedDevices->setLoopNo($device->getLoopNo());
                $inspectedDevices->setNumber($device->getNumber());
                $inspectedDevices->setShortname($device->getShortname());

                $inspectedDevices->setFireProtectionDevice($device);
                $device->addInspectedDevice($inspectedDevices);

                array_push($missingInspectedDevices, $serializer->serialize($inspectedDevices, 'json'));

                $inspectedDevices->setFireInspection($fireInspection);
                $fireInspection->addInspectedDevice($inspectedDevices);


                $em->persist($inspectedDevices);
                $em->flush();

            }

        }


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $jsonData['missing-devices'] = $missingInspectedDevices;

            return new JsonResponse($jsonData);
        }


    }


}
