<?php

namespace MicroBundle\Controller;

use MicroBundle\Entity\DeviceName;
use MicroBundle\Entity\FireProtectionDevice;
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

        $fireProtectionDevices = $em->getRepository('MicroBundle:FireProtectionDevice')->findAll();

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
        $fireProtectionDevice = new Fireprotectiondevice();
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
    public function showAction(FireProtectionDevice $fireProtectionDevice)
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
    public function editAction(Request $request, FireProtectionDevice $fireProtectionDevice)
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
    public function deleteAction(Request $request, FireProtectionDevice $fireProtectionDevice)
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
     * @param FireProtectionDevice $fireProtectionDevice The fireProtectionDevice entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(FireProtectionDevice $fireProtectionDevice)
    {
        return $this->createFormBuilder()->setAction($this->generateUrl('fireprotectiondevice_delete', array('id' => $fireProtectionDevice->getId())))->setMethod('DELETE')->getForm();

    }

    /**
     * Get FireProtectionDevice object
     * @Method({"GET", "POST"})
     * @Route("/get-device/{id}")
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function getFireProtectionDeviceAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $fireProtectionDevice = $em->getRepository('MicroBundle:FireProtectionDevice')->findOneBy(['id' => $id]);
        $nameId = '1';
        $deviceName = $em->getRepository('MicroBundle:DeviceName')->findOneBy(['name' => $fireProtectionDevice->getName()]);

        if (!$deviceName == null) {
            $nameId = $deviceName->getId();
        }


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
            $jsonData['nameId'] = $nameId;


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

        if ($id == "null") {
            $fireProtectionDevice = new FireProtectionDevice();
            $loop = $em->getRepository('MicroBundle:LoopDev')->findOneBy(['id' => $loop]);

            $loop->addFireProtectionDevice($fireProtectionDevice);


            $fireProtectionDevice->setLoopDev($loop);
            $fireProtectionDevice->setnumber($number);

        } else {
            $fireProtectionDevice = $em->getRepository('MicroBundle:FireProtectionDevice')->findOneBy(['id' => $id]);

        }

        if ($name != "null") {
            $deviceName = $em->getRepository('MicroBundle:DeviceName')->findOneBy(['name' => $name]);
            if (!$deviceName instanceof DeviceName) {
                $deviceName = $em->getRepository('MicroBundle:DeviceName')->findOneBy(['id' => $name]);
            }
            $name = $deviceName->getName();
            $shortname = $deviceName->getShortname();
            $fireProtectionDevice->setName($name);
            $fireProtectionDevice->setShortname($shortname);


        }


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
        //get loop id if it possible
        $loopid = ($loop == "null") ? "null" : $loop->getId();

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

        $fireProtectionDevice = $em->getRepository('MicroBundle:FireProtectionDevice')->findOneBy(['id' => $id]);

        $loopDev = $fireProtectionDevice->getLoopDev();
        $loopId = $loopDev->getId();

        $loopDev->removeFireProtectionDevice($fireProtectionDevice);
        $fireProtectionDevice->setLoopDev(null);

        $em->remove($fireProtectionDevice);
        $em->flush();


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {


            $jsonData['id'] = $id;
            $jsonData['loop'] = $loopId;


            return new JsonResponse($jsonData);
        }
    }
}
