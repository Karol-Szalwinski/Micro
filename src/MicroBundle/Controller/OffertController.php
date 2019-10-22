<?php

namespace MicroBundle\Controller;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use MicroBundle\Entity\Client;
use MicroBundle\Entity\Offert;
use MicroBundle\Enums\OffertStatusEnum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Offert controller.
 *
 * @Route("offert")
 */
class OffertController extends Controller
{
    /**
     * Lists all offert entities.
     *
     * @Route("/", name="offert_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $offerts = $em->getRepository('MicroBundle:Offert')->findAll();

        $today = new DateTime;
        $nextWeek = new DateTime("+1 Week");
        foreach ($offerts as $k => $offert) {
            if ($offert->getStatus() != OffertStatusEnum::BASKET) {

                if ($offert->getExpireDate() < $today) {
                    $offert->setStatus(OffertStatusEnum::CLOSED);
                } else if ($offert->getExpireDate() < $nextWeek) {
                    $offert->setStatus(OffertStatusEnum::EXPIRE);
                } else {
                    $offert->setStatus(OffertStatusEnum::ACTIVE);
                }
            } else {
                unset($offerts[$k]);
            }
        }
        $em->flush();

        return $this->render('offert/index.html.twig', array('offerts' => $offerts,));
    }

    /**
     * Creates a new offert entity.
     *
     * @Route("/new", name="offert_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $offert = new Offert();
        $form = $this->createForm('MicroBundle\Form\OffertType', $offert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($offert);
            $em->flush();

            return $this->redirectToRoute('offert_show', array('id' => $offert->getId()));
        }

        return $this->render('offert/new.html.twig', array('offert' => $offert, 'form' => $form->createView(),));
    }

    /**
     * Finds and displays a offert entity.
     *
     * @Route("/{id}", name="offert_show")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Offert $offert
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request, Offert $offert)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('MicroBundle:Product')->findAll();
        $clients = $em->getRepository('MicroBundle:Client')->findAll();

        //backup OffPositions and OffServices
        $originalOffPositions = new ArrayCollection();
        $originalOffServices = new ArrayCollection();
        foreach ($offert->getOffPositions() as $offPosition) {
            $originalOffPositions->add($offPosition);
        }
        foreach ($offert->getoffServices() as $offService) {
            $originalOffServices->add($offService);
        }
        // add client if not exist end backup ol client id
        $oldClientId = null;
        $oldClient = $offert->getClients()->first();

        if ($oldClient) {
            $oldClientId = $oldClient->getId();
        } else {
            $offert->addClient(new Client("Klient detaliczny"));
        }


        $form = $this->createForm('MicroBundle\Form\OffertType', $offert);
        $form->handleRequest($request);
//        && $form->isValid()
        if ($form->isSubmitted()) {
            // remove the relationship between the Offert and the OffPositions and delete them
            foreach ($originalOffPositions as $offPosition) {
                if (false === $offert->getOffPositions()->contains($offPosition)) {
                    $offert->removeOffPosition($offPosition);
                    $offPosition->setOffert(null);

                    $em->remove($offPosition);

                }
            }
            foreach ($originalOffServices as $offService) {
                if (false === $offert->getOffServices()->contains($offService)) {
                    $offert->removeOffService($offService);
                    $offService->setOffert(null);

                    $em->remove($offService);

                }
            }
            foreach ($offert->getOffPositions() as $offPosition) {
                $prodId = $offPosition->getProductId();
                if ($prodId) {
                    $product = $em->getRepository('MicroBundle:Product')->findOneById($prodId);
                    if ($product) {
                        $offPosition->setProduct($product);
                    }
                }
            }//client from form
            $clientForm = $offert->getClients()->first();
            $clientId = $clientForm->getId();
            dump($oldClient);
            dump($offert);
            //todo refactor
            //if client have no id - is new
            if (!$clientId) {
                //if was old one before
                if ($oldClientId) {
                    //create new
                    $newClient = new Client();

                    $newClient->SetName($clientForm->getName());
                    $newClient->SetStreet($clientForm->getStreet());
                    $newClient->SetHouseNo($clientForm->getHouseNo());
                    $newClient->SetFlatNo($clientForm->getFlatNo());
                    $newClient->SetCity($clientForm->getCity());
                    $newClient->SetPostalCode($clientForm->getPostalCode());


                    $offert->addClient($newClient);
                    $em->persist($newClient);

                    //remove old
                    $offert->removeClient($clientForm);


                } else {
                    //if dont was old we must only persist new one from form
                    $em->persist($clientForm);
                }


            }

            if ($clientId && $clientId != $oldClientId) {
                $dbClient = $em->getRepository('MicroBundle:Client')->findOneBy(['id' => $clientId]);
                $dbClient->SetName($clientForm->getName());
                $dbClient->SetStreet($clientForm->getStreet());
                $dbClient->SetHouseNo($clientForm->getHouseNo());
                $dbClient->SetFlatNo($clientForm->getFlatNo());
                $dbClient->SetCity($clientForm->getCity());
                $dbClient->SetPostalCode($clientForm->getPostalCode());


                $offert->addClient($dbClient);
                $offert->removeClient($clientForm);

                //odpuść zmiany w starym elemencie
                dump($dbClient);
                dump($offert);
            }
            dump($offert);
            dump($offert->getClients()->first());
            $em->flush($offert->getClients()->first());
            $em->flush($offert);
            return $this->redirectToRoute('offert_index');
        }

        return $this->render('offert/show.html.twig', array('offert' => $offert, 'products' => $products, 'clients' => $clients, 'form' => $form->createView(),));
    }

    /**
     * Displays a form to edit an existing offert entity.
     *
     * @Route("/{id}/edit", name="offert_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Offert $offert)
    {
        $deleteForm = $this->createDeleteForm($offert);
        $editForm = $this->createForm('MicroBundle\Form\OffertType', $offert);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('offert_edit', array('id' => $offert->getId()));
        }

        return $this->render('offert/edit.html.twig', array('offert' => $offert, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Deletes a offert entity.
     *
     * @Route("/{id}", name="offert_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Offert $offert)
    {
        $form = $this->createDeleteForm($offert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($offert);
            $em->flush();
        }

        return $this->redirectToRoute('offert_index');
    }

    /**
     * Creates a form to delete a offert entity.
     *
     * @param Offert $offert The offert entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Offert $offert)
    {
        return $this->createFormBuilder()->setAction($this->generateUrl('offert_delete', array('id' => $offert->getId())))->setMethod('DELETE')->getForm();
    }
}
