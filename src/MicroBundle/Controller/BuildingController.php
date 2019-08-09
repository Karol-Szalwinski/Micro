<?php

namespace MicroBundle\Controller;

use MicroBundle\Entity\Building;
use MicroBundle\Entity\Client;
use MicroBundle\Entity\FireProtectionDevice;
use MicroBundle\Entity\LoopDev;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * Building controller.
 *
 * @Route("building")
 */
class BuildingController extends Controller
{
    /**
     * Lists all building entities.
     *
     * @Route("/", name="building_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $buildings = $em->getRepository('MicroBundle:Building')->findAll();

        return $this->render('building/index.html.twig', array('buildings' => $buildings,));
    }

    /**
     * Creates a new building entity.
     *
     * @Route("/new/{client}", name="building_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Client $client, Request $request)
    {
        $building = new Building();
        $building->setDeviceShortlistPosition("Centrala NAZWA \nCzujki optyczne dymu\nCzujki termiczne\nSygnalizatory optyczno-akustyczne\n" . "Ręczne ostrzegacze pożarowe\nDrukarki termiczne");
        $form = $this->createForm('MicroBundle\Form\BuildingType', $building);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $building->setClient($client);
            $client->addBuilding($building);
            $em = $this->getDoctrine()->getManager();
            $em->persist($building);
            $em->flush();

            return $this->redirectToRoute('building_show', array('id' => $building->getId()));
        }

        return $this->render('building/new.html.twig', array('building' => $building, 'client' => $client, 'form' => $form->createView(),));
    }

    /**
     * Finds and displays a building entity.
     *
     * @Route("/{id}", name="building_show")
     * @Method("GET")
     */
    public function showAction(Building $building, Request $request)
    {

        $loopDev = new LoopDev();
        //setting new loop number
        $loopDev->setNumber($building->countLoopDevsWithoutDel() + 1);
        $loopForm = $this->createForm('MicroBundle\Form\LoopDevType', $loopDev);
        $loopForm->handleRequest($request);

        $tempFireProtectionDevice = new Fireprotectiondevice();
        $editForm = $this->createForm('MicroBundle\Form\FireProtectionDeviceEditType', $tempFireProtectionDevice);
        $addForm = $this->createForm('MicroBundle\Form\FireProtectionDeviceType', $tempFireProtectionDevice);


        //Form to add Loop
        if ($loopForm->isSubmitted() && $loopForm->isValid()) {
            $quantityDevices = $loopDev->getQuantityDevices();

            $em = $this->getDoctrine()->getManager();
//check if loopDev exist
            $loopOldDev = $em->getRepository('MicroBundle:LoopDev')->findOneBy(['building' => $building->getId(), 'number' => $loopDev->getNumber()]);

            if ($loopOldDev instanceof LoopDev) {
                $loopDev = $loopOldDev;
                $loopDev->setDel(false);
                for ($i = 1; $i <= $quantityDevices; $i++) {
                    $fireProtectionDevice = $em->getRepository('MicroBundle:FireProtectionDevice')
                        ->findOneBy(['loopDev' => $loopDev->getId(), 'number' => $i]);
                    if ($fireProtectionDevice instanceof FireProtectionDevice) {
                        $fireProtectionDevice->setDel(false);

                    }
                    else {
                        $fireProtectionDevice = new FireProtectionDevice();
                        $fireProtectionDevice->setNumber($i);
                        $fireProtectionDevice->setLoopDev($loopDev);
                        $loopDev->addFireProtectionDevice($fireProtectionDevice);
                        $em->persist($fireProtectionDevice);

                    }
                }

            } else {
                $building->addLoopDev($loopDev);
                $loopDev->setBuilding($building);


                for ($i = 1; $i <= $quantityDevices; $i++) {
                    $fireProtectionDevice = new FireProtectionDevice();
                    $fireProtectionDevice->setNumber($i);
                    $fireProtectionDevice->setLoopDev($loopDev);
                    $loopDev->addFireProtectionDevice($fireProtectionDevice);
                    $em->persist($fireProtectionDevice);
                }
                $em->persist($loopDev);
            }


            $em->flush();

            return $this->redirectToRoute('building_show', array('id' => $building->getId()));
        }
        $this->container->get('micro')->updateLastServiceDate($building);

        return $this->render('building/show.html.twig', array('building' => $building, 'form' => $addForm->createView(), 'loop_form' => $loopForm->createView(), 'edit_form' => $editForm->createView()));
    }

    /**
     * Displays a form to edit an existing building entity.
     *
     * @Route("/{id}/edit", name="building_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Building $building)
    {
        $deleteForm = $this->createDeleteForm($building);
        $editForm = $this->createForm('MicroBundle\Form\BuildingType', $building);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('building_index');
        }

        return $this->render('building/edit.html.twig', array('building' => $building, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Deletes a building entity.
     *
     * @Route("/{id}", name="building_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Building $building)
    {
        $form = $this->createDeleteForm($building);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($building);
            $em->flush();
        }

        return $this->redirectToRoute('building_index');
    }

    /**
     * Creates a form to delete a building entity.
     *
     * @param Building $building The building entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Building $building)
    {
        return $this->createFormBuilder()->setAction($this->generateUrl('building_delete', array('id' => $building->getId())))->setMethod('DELETE')->getForm();
    }
}
