<?php

namespace MicroBundle\Controller;

use MicroBundle\Entity\Device;
use MicroBundle\Entity\BuildDevice;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Fireprotectiondevice controller.
 *
 * @Route("build-device")
 */
class BuildDeviceController extends Controller
{



    /**
     * Get BuildDevice object
     * @Method({"GET", "POST"})
     * @Route("/get-device/{id}")
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function getFireProtectionDeviceAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $fireProtectionDevice = $em->getRepository('MicroBundle:BuildDevice')->findOneBy(['id' => $id]);
        $nameId = '1';
        $deviceName = $em->getRepository('MicroBundle:Device')->findOneBy(['name' => $fireProtectionDevice->getName()]);

        if (!$deviceName == null) {
            $nameId = $deviceName->getId();
        }


        //remove refference
        $fireProtectionDevice->removeAllInspectedDevices()->setLoopDev(null);
        //Change date to string

        $tempDate = ($fireProtectionDevice->getTempServiceDate()) ? $fireProtectionDevice->getTempServiceDate()->format('Y-m-d') : "brak";
        $fireProtectionDevice->setTempServiceDate($tempDate);


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            //prepare serializer
            $normalizer = new ObjectNormalizer();
//            $normalizer->setCircularReferenceLimit(0);
//            // Add Circular reference handler
//            $normalizer->setCircularReferenceHandler(function ($object) {
//                return $object->getId();
//            });

            $normalizers = [$normalizer];
            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $serializer = new Serializer($normalizers, $encoders);
            $serializeDevice = $serializer->serialize($fireProtectionDevice, 'json');

            $jsonData['device'] = $serializeDevice;
//            dump($serializeDevice);die();
            $jsonData['nameId'] = $nameId;
            $jsonData['tempServiceDate'] = $fireProtectionDevice->getTempServiceDate();


            return new JsonResponse($jsonData);
        }
    }


    /**
     * Add or Update Fire Protection Device
     * @Method({"GET", "POST"})
     * @Route("/update-device/{id}/{loop}/{number}/{name}/{serial}/{address}/{desc}")
     * @param Request $request
     * @param $id
     * @param $loop
     * @param $number
     * @param $name
     * @param $serial
     * @param $address
     * @param $desc
     * @return JsonResponse
     */
    public function updateFireProtectionDeviceAction(Request $request, $id, $loop, $number, $name, $serial, $address, $desc)
    {
        $em = $this->getDoctrine()->getManager();
        //if we dont have id
        if ($id == "null") {
        //try to search deleted device with the same number and loopdev
            $fireProtectionDevice = $em->getRepository('MicroBundle:BuildDevice')->findOneBy(['number' => $number, 'loopDev'=> $loop]);

            //if found change status on undeleted
            if ($fireProtectionDevice instanceof BuildDevice) {
                $fireProtectionDevice->setDel(false);
                //if not we create a new object
            } else {
                $fireProtectionDevice = new BuildDevice();

                $loopDev = $em->getRepository('MicroBundle:LoopDev')->findOneBy(['id' => $loop]);
                $loopDev->addFireProtectionDevice($fireProtectionDevice);

                $fireProtectionDevice->setLoopDev($loopDev);
                $fireProtectionDevice->setnumber($number);
            }

        } else {
            $fireProtectionDevice = $em->getRepository('MicroBundle:BuildDevice')->findOneBy(['id' => $id]);
        }

        if ($name != "null") {
            $deviceName = $em->getRepository('MicroBundle:Device')->findOneBy(['name' => $name]);
            if (!$deviceName instanceof Device) {
                $deviceName = $em->getRepository('MicroBundle:Device')->findOneBy(['id' => $name]);
            }
            $name = $deviceName->getName();
            $shortname = $deviceName->getShortname();
            $fireProtectionDevice->setName($name);
            $fireProtectionDevice->setShortname($shortname);

        }
        //get loop id
        $loopid = $fireProtectionDevice->getLoopDev()->getId();

    //prepare to serialization
        //chane nulles on ""
        $serial = ($serial == "null") ? "" : $serial;
        $address = ($address == "null") ? "" : $address;
        $desc = ($desc == "null") ? "" : $desc;


        $fireProtectionDevice->setSerial($serial);
        $fireProtectionDevice->setAddress($address);
        $fireProtectionDevice->setDesc($desc);
        $em->flush();


        //remove refference
        $fireProtectionDevice->removeAllInspectedDevices()->setLoopDev(null);


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            //prepare serializer
            $normalizer = new ObjectNormalizer();
            $normalizer->setCircularReferenceLimit(0);
            // Add Circular reference handler
            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getId();
            });

            $normalizers = [$normalizer];
            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $serializer = new Serializer($normalizers, $encoders);
            $serializeDevice = $serializer->serialize($fireProtectionDevice, 'json');

            $jsonData['device'] = $serializeDevice;
            $jsonData['loopid'] = $loopid;


            return new JsonResponse($jsonData);
        }
    }

    /**
     * Add or Update Fire Protection Device
     * @Method({"GET", "POST"})
     * @Route("/delete-device/{id}")
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function deleteFireProtectionDeviceAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $fireProtectionDevice = $em->getRepository('MicroBundle:BuildDevice')->findOneBy(['id' => $id]);

        $fireProtectionDevice->setDel(true);
        $em->flush();
        $loopId = $fireProtectionDevice->getLoopDev()->getId();


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $normalizers = [new ObjectNormalizer()];
            $encoders = [new JsonEncoder()];
            $serializer = new Serializer($normalizers, $encoders);
            $serializeDevice = $serializer->serialize($fireProtectionDevice, 'json');

            $jsonData['id'] = $id;
            $jsonData['loop'] = $loopId;


            return new JsonResponse($jsonData);
        }

    }
/////////////////////////////////////////////NEW/////////////////////////////////////////////////////

    /**
     * Update BuildDevice
     * @Method({"GET", "POST"})
     * @Route("/update/{jsondevice}", name="build_device_update")
     * @param Request $request
     * @return JsonResponse
     */
    public function updateAction(Request $request, $jsondevice)
    {
        $em = $this->getDoctrine()->getManager();

        $device = json_decode($jsondevice);

        $buildDevice = $em->getRepository('MicroBundle:BuildDevice')->findOneBy(['id' => $device->{'id'}]);
        $deviceName = $em->getRepository('MicroBundle:Device')->findOneBy(['shortname' => $device->{'shortname'}]);
        $name = ($deviceName) ? $deviceName->getName() : "";

        $buildDevice->setNumber($device->{'number'});
        $buildDevice->setShortname($device->{'shortname'});
        $buildDevice->setName($name);
        $buildDevice->setSerial($device->{'serial'});
        $buildDevice->setAddress($device->{'address'});
        $em->flush();


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $normalizer = new ObjectNormalizer();
            $normalizer->setIgnoredAttributes(['building', 'docDevices']);
            $encoder = new JsonEncoder();

            $serializer = new Serializer([$normalizer], [$encoder]);
            $serializeDevice = $serializer->serialize($buildDevice, 'json');


            $jsonData['device'] = $serializeDevice;



            return new JsonResponse($jsonData);
        }
    }

    /**
     * Add BuildDevice
     * @Method({"GET", "POST"})
     * @Route("/add/{buildingId}/{loopNo}", name="build_device_add")
     * @param Request $request
     * @return JsonResponse
     */
    public function addAction(Request $request, $buildingId, $loopNo)
    {
        $em = $this->getDoctrine()->getManager();

        $maxNumber = $em->getRepository('MicroBundle:BuildDevice')->getHighestNumber($buildingId, $loopNo);
        $nextNumber = ++$maxNumber['maxNumber'];
        $building = $em->getRepository('MicroBundle:Building')->findOneBy(['id' => $buildingId]);

        $buildDevice = new BuildDevice;
        $buildDevice->setNumber($nextNumber);
        $buildDevice->setLoopNo($loopNo);
        $buildDevice->setBuilding($building);
        $building->addBuildDevice($buildDevice);
        $em->persist($buildDevice);
        $em->flush();

        $devices=$em->getRepository('MicroBundle:Device')->findAll();
        $shortnames=[];
        foreach ($devices as $device) {
            $shortnames[]=$device->getShortname();
        }

        $next = $em->getRepository('MicroBundle:BuildDevice')->count(['building' => $building, 'loopNo' => $loopNo, 'del' => false]);

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $normalizer = new ObjectNormalizer();
            $normalizer->setIgnoredAttributes(['building', 'docDevices']);
            $encoder = new JsonEncoder();

            $serializer = new Serializer([$normalizer], [$encoder]);
            $serializeDevice = $serializer->serialize($buildDevice, 'json');


            $jsonData['device'] = $serializeDevice;
            $jsonData['next'] = $next;
            $jsonData['shortnames'] = $shortnames;



            return new JsonResponse($jsonData);
        }
    }

    /**
     * Delete BuildDevice
     * @Method({"POST"})
     * @Route("/delete/{deviceId}", name="build_device_delete")
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAction(Request $request, $deviceId) {
        $em = $this->getDoctrine()->getManager();

        $buildDevice = $em->getRepository('MicroBundle:BuildDevice')->findOneBy(['id' => $deviceId]);

        $buildDevice->setDel(true);
        $em->flush();


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $jsonData['id'] = $deviceId;


            return new JsonResponse($jsonData);
        }


    }
}
