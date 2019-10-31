<?php

namespace MicroBundle\Controller;

use MicroBundle\Entity\Building;
use MicroBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Client controller.
 *
 * @Route("client")
 */
class ClientController extends Controller
{
    /**
     * Lists all client entities.
     *
     * @Route("/", name="client_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $clients = $em->getRepository('MicroBundle:Client')->findAll();
        $mainCategories = $em->getRepository('MicroBundle:Category')->findBy(['parent' => null]);

        return $this->render('client/index.html.twig', array(
            'clients' => $clients,
            'mainCategories' => $mainCategories
        ));
    }

    /**
     * Creates a new client entity.
     *
     * @Route("/new", name="client_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $mainCategories = $em->getRepository('MicroBundle:Category')->findBy(['parent' => null]);
        $client = new Client();
        $form = $this->createForm('MicroBundle\Form\ClientType', $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $em->persist($client);
            $em->flush();

            return $this->redirectToRoute('client_show', array('id' => $client->getId()));
        }

        return $this->render('client/new.html.twig', array(
            'client' => $client,
            'form' => $form->createView(),
            'mainCategories' => $mainCategories
        ));
    }

    /**
     * Finds and displays a client entity.
     *
     * @Route("/{id}", name="client_show")
     * @Method("GET")
     */
    public function showAction(Client $client)
    {
        $em = $this->getDoctrine()->getManager();
        $mainCategories = $em->getRepository('MicroBundle:Category')->findBy(['parent' => null]);

        return $this->render('client/show.html.twig', array(
            'client' => $client,
            'mainCategories' => $mainCategories
        ));
    }

    /**
     * Displays a form to edit an existing client entity.
     *
     * @Route("/{id}/edit", name="client_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Client $client)
    {
        $em = $this->getDoctrine()->getManager();
        $mainCategories = $em->getRepository('MicroBundle:Category')->findBy(['parent' => null]);
        $editForm = $this->createForm('MicroBundle\Form\ClientType', $client);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();

            return $this->redirectToRoute('client_show', array('id' => $client->getId()));
        }

        return $this->render('client/edit.html.twig', array(
            'client' => $client,
            'edit_form' => $editForm->createView(),
            'mainCategories' => $mainCategories
        ));
    }

    /**
     * Set client as deleted
     *
     * @Route("/delete/{id}", name="client_delete")
     * @Method("POST")
     * @param Client $client
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Client $client)
    {

        $em = $this->getDoctrine()->getManager();

        $client->setDeleted();

        foreach($client->getBuildings() as $building){


            foreach($building->getDocuments() as $document){

                $document->setDeleted();

                foreach($document->getDocDevices() as $docDevice){
                    $docDevice->setVisible(false);
                }
                $building->removeDocument($document);
                $document->setBuilding(null);

            }
            $building->getClient()->removeBuilding($building);
            $building->setClient(null);

        }

        foreach($client->getOfferts() as $offert) {
            $client->removeOffert($offert);
            $offert->removeClient($client);

        }

        $em->flush();

        return $this->redirectToRoute('client_index');
    }

    /**
     * Set Client's building as deleted
     *
     * @Route("/{{id}/delete/{building}", name="client_delete_building")
     * @Method("POST")
     * @param Client $client
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteBuildingAction(Client $client, Building $building)
    {

        $building->setDeleted();

        foreach($building->getDocuments() as $document){

            $document->setDeleted();

            foreach($document->getDocDevices() as $docDevice){
                $docDevice->setVisible(false);
            }
            $building->removeDocument($document);
            $document->setBuilding(null);

        }
        $client->removeBuilding($building);
        $building->setClient(null);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('client_show', [
            'id' => $client->getId()
        ]);
    }


}
