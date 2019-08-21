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
 * @Route("fireprotectiondevice")
 */
class FireProtectionDeviceController extends Controller
{
    /**
     * Lists all fireProtectionDevice entities.
     *
     * @Route("/", name="fireprotectiondevice_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $fireProtectionDevices = $em->getRepository('MicroBundle:BuildDevice')->findAll();

        return $this->render('fireprotectiondevice/index.html.twig', array('fireProtectionDevices' => $fireProtectionDevices,));
    }

    /**
     * Creates a new fireProtectionDevice entity.
     *
     * @Route("/new", name="fireprotectiondevice_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $fireProtectionDevice = new BuildDevice();
        $form = $this->createForm('MicroBundle\Form\FireProtectionDeviceType', $fireProtectionDevice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fireProtectionDevice);
            $em->flush();

            return $this->redirectToRoute('fireprotectiondevice_show', array('id' => $fireProtectionDevice->getId()));
        }

        return $this->render('fireprotectiondevice/new.html.twig', array('fireProtectionDevice' => $fireProtectionDevice, 'form' => $form->createView(),));
    }

    /**
     * Finds and displays a fireProtectionDevice entity.
     *
     * @Route("/{id}", name="fireprotectiondevice_show")
     * @Method("GET")
     */
    public function showAction(BuildDevice $fireProtectionDevice)
    {
        $deleteForm = $this->createDeleteForm($fireProtectionDevice);

        return $this->render('fireprotectiondevice/show.html.twig', array('fireProtectionDevice' => $fireProtectionDevice, 'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing fireProtectionDevice entity.
     *
     * @Route("/{id}/edit", name="fireprotectiondevice_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, BuildDevice $fireProtectionDevice)
    {
        $deleteForm = $this->createDeleteForm($fireProtectionDevice);
        $editForm = $this->createForm('MicroBundle\Form\FireProtectionDeviceType', $fireProtectionDevice);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('fireprotectiondevice_edit', array('id' => $fireProtectionDevice->getId()));
        }

        return $this->render('fireprotectiondevice/edit.html.twig', array('fireProtectionDevice' => $fireProtectionDevice, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Deletes a fireProtectionDevice entity.
     *
     * @Route("/{id}", name="fireprotectiondevice_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, BuildDevice $fireProtectionDevice)
    {
        $form = $this->createDeleteForm($fireProtectionDevice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($fireProtectionDevice);
            $em->flush();
        }

        return $this->redirectToRoute('fireprotectiondevice_index');
    }

    /**
     * Creates a form to delete a fireProtectionDevice entity.
     *
     * @param BuildDevice $fireProtectionDevice The fireProtectionDevice entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(BuildDevice $fireProtectionDevice)
    {
        return $this->createFormBuilder()->setAction($this->generateUrl('fireprotectiondevice_delete', array('id' => $fireProtectionDevice->getId())))->setMethod('DELETE')->getForm();

    }

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


            $jsonData['id'] = $id;
            $jsonData['loop'] = $loopId;


            return new JsonResponse($jsonData);
        }
    }
}
