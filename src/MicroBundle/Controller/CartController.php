<?php

namespace MicroBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use MicroBundle\Entity\Offert;
use MicroBundle\Enums\OffertStatusEnum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Cart controller.
 *
 * @Route("cart")
 */
class CartController extends Controller
{


    /**
     * Finds and displays a offert entity.
     *
     * @Route("/", name="cart_show")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('MicroBundle:Product')->findAll();
        $offert = $em->getRepository('MicroBundle:Offert')->findOneBy(['status' => OffertStatusEnum::BASKET]);

        //backup OffPositions and OffServices
        $originalOffPositions = new ArrayCollection();
        $originalOffServices = new ArrayCollection();
        foreach ($offert->getOffPositions() as $offPosition) {
            $originalOffPositions->add($offPosition);
        }


        $form = $this->createForm('MicroBundle\Form\CartType', $offert);
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
            foreach ($offert->getOffPositions() as $offPosition) {
                $prodId = $offPosition->getProductId();
                if ($prodId) {
                    $product = $em->getRepository('MicroBundle:Product')->findOneById($prodId);
                    if ($product) {
                        $offPosition->setProduct($product);
                    }
                }
            }
            $nextOffertNumber= $em->getRepository('MicroBundle:Offert')->Count([]) + 1;
            $offert->setName("Oferta " . $nextOffertNumber );
            $offert->setStatus(OffertStatusEnum::ACTIVE);
            $offert->setMyCompany($this->container->get('mycompany')->getOrCreateDefaultMyCompany());


            //close cart and create new
            $newCart = new Offert();

            $em->persist($newCart);
            $em->flush();

            return $this->redirectToRoute('offert_show', ['id' => $offert->getId()]);
        }

        return $this->render('cart/show.html.twig', array('offert' => $offert, 'products' => $products, 'form' => $form->createView(),));
    }


}
