<?php

namespace MicroBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use MicroBundle\Entity\Product;
use MicroBundle\Entity\ProductParameter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Product controller.
 *
 * @Route("product")
 */
class ProductController extends Controller
{
    /**
     * Lists all product entities.
     *
     * @Route("/", name="product_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('MicroBundle:Product')->findAll();
        $mainCategories = $em->getRepository('MicroBundle:Category')->findBy(['parent' => null]);

        return $this->render('product/index.html.twig', array('products' => $products, 'mainCategories' => $mainCategories,));
    }
    /**
     * Lists all product by category.
     *
     * @Route("/category/{categoryId}", name="product_index_category")
     * @Method("GET")
     */
    public function showByCategoryAction($categoryId)
    {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('MicroBundle:Category')->findOneBy(['id' => $categoryId]);

        $products = $em->getRepository('MicroBundle:Product')->findBy(['category' => $categoryId]);

        foreach ($category->getChildren() as $child) {
            $childProducts = $em->getRepository('MicroBundle:Product')->findBy(['category' => $child->getId()]);
            $products = array_merge($products,$childProducts);
            dump($childProducts);
        }
        $mainCategories = $em->getRepository('MicroBundle:Category')->findBy(['parent' => null]);

        return $this->render('product/index_cat.html.twig', array('products' => $products, 'category' => $category, 'mainCategories' => $mainCategories,));
    }

    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="product_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('MicroBundle:Category')->findAll();
        $product = new Product();
        $form = $this->createForm('MicroBundle\Form\ProductType', $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($product);
            //add parameters to productParameter
            $product = $this->container->get('parameters')->connectNewProductParameterWithParent($product);
            dump($product);
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('product/new.html.twig', array('product' => $product, 'categories' => $categories, 'form' => $form->createView(),));
    }

    /**
     * Finds and displays a product entity.
     *
     * @Route("/{id}", name="product_show")
     * @Method("GET")
     */
    public function showAction(Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);

        return $this->render('product/show.html.twig', array('product' => $product, 'delete_form' => $deleteForm->createView(),));
    }



    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="product_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Product $product)
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('MicroBundle:Category')->findAll();

        //backup ProductParameters
        $originalProductParameters = new ArrayCollection();
        foreach ($product->getProductParameters() as $productParameter) {
            $originalProductParameters->add($productParameter);
        }
        $product = $this->container->get('parameters')->updateProductParameters($product);

        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('MicroBundle\Form\ProductType', $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            // remove the relationship between the Product and the ProductParameters and delete them
            foreach ($originalProductParameters as $productParameter) {
                if (false === $product->getProductParameters()->contains($productParameter)) {
                    $product->removeProductParameter($productParameter);
                    $productParameter->setProduct(null);

                    $em->remove($productParameter);

                }
            }
            $product = $this->container->get('parameters')->connectNewProductParameterWithParent($product);

            $em->flush();

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('product/edit.html.twig', array('product' => $product, 'categories' => $categories, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/{id}", name="product_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Product $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))->setMethod('DELETE')->getForm();
    }

    /**
     * get product info view
     * @Method({"POST"})
     * @Route("/get/{productId}", name="product_get_ajax")
     * @param Request $request
     * @return JsonResponse
     */
    public function getCategoryAjaxAction(Request $request, $productId)
    {
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('MicroBundle:Product')->findOneBy(['id' => $productId]);

        $productView = $this->render('product/modal_content.html.twig', array('product' => $product))->getContent();


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $jsonData['productView'] = $productView;

            return new JsonResponse($jsonData);
        }


    }
}
