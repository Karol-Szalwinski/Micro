<?php

namespace MicroBundle\Controller;

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
     * Change status or test in InspectedDevice
     * @Method({"GET", "POST"})
     * @Route("/return-fire-protection-device/{id}")
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function returnFireProtectionDeviceAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $fireProtectionDevice = $em->getRepository('MicroBundle:FireProtectionDevice')->findOneBy(['id' => $id]);
        $nameVal = $em->getRepository('MicroBundle:DeviceName')
            ->findOneBy(['name' => $fireProtectionDevice->getName()]);
       if ($nameVal==null){
           $nameVal='1';
       } else
       {
           $nameVal=$nameVal->getId();
       }

        $fireProtectionDevice->setName($nameVal);
        //remove refference
        $fireProtectionDevice->removeAllInspectedDevices()->setLoopDev(null);

//        dump($fireProtectionDevice);die();

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


            return new JsonResponse($jsonData);
        }
    }
}
