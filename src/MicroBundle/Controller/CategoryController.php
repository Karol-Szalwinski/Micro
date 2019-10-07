<?php

namespace MicroBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use MicroBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Category controller.
 *
 * @Route("category")
 */
class CategoryController extends Controller
{
    /**
     * Lists all category entities.
     *
     * @Route("/", name="category_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('MicroBundle:Category')->findAll();
        $mainCategories = $em->getRepository('MicroBundle:Category')->findBy(['parent' => null]);

        return $this->render('category/index.html.twig', array('categories' => $categories,'mainCategories' => $mainCategories,));
    }

    /**
     * Creates a new category entity.
     *
     * @Route("/new", name="category_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('MicroBundle:Category')->findAll();

        $category = new Category();
        $form = $this->createForm('MicroBundle\Form\CategoryType', $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_show', array('id' => $category->getId()));
        }

        return $this->render('category/new.html.twig', array('category' => $category, 'categories' => $categories, 'form' => $form->createView(),));
    }

    /**
     * Finds and displays a category entity.
     *
     * @Route("/{id}", name="category_show")
     * @Method("GET")
     */
    public function showAction(Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);

        return $this->render('category/show.html.twig', array('category' => $category, 'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing category entity.
     *
     * @Route("/{id}/edit", name="category_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('MicroBundle:Category')->findAll();

        $originalParameters = new ArrayCollection();
        foreach ($category->getParameters() as $parameter) {
            $originalParameters->add($parameter);
        }

        $deleteForm = $this->createDeleteForm($category);
        $editForm = $this->createForm('MicroBundle\Form\CategoryType', $category);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->container->get('parameters')->handleChangesInParameters($category, $originalParameters, $em);
            $em->flush();

            return $this->redirectToRoute('category_show', array('id' => $category->getId()));
        }

        return $this->render('category/edit.html.twig', array('category' => $category, 'categories' => $categories, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Deletes a category entity.
     *
     * @Route("/{id}", name="category_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Category $category)
    {
        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }

        return $this->redirectToRoute('category_index');
    }

    /**
     * Creates a form to delete a category entity.
     *
     * @param Category $category The category entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Category $category)
    {
        return $this->createFormBuilder()->setAction($this->generateUrl('category_delete', array('id' => $category->getId())))->setMethod('DELETE')->getForm();
    }

    /**
     * get category path
     * @Method({"POST"})
     * @Route("/get/{categoryId}", name="category_get_ajax")
     * @param Request $request
     * @return JsonResponse
     */
    public function getCategoryAjaxAction(Request $request, $categoryId)
    {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('MicroBundle:Category')->findOneBy(['id' => $categoryId]);

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $serializedCategory = $this->container->get('serialize')->serlializeJson($category, 1, []);

            $jsonData['category'] = $serializedCategory;

            return new JsonResponse($jsonData);
        }


    }


    /**
     * get category children
     * @Method({"POST"})
     * @Route("/get-children/{categoryId}", name="category_get_children_ajax")
     * @param Request $request
     * @return JsonResponse
     */
    public function getChildrenAction(Request $request, $categoryId)
    {
        $serializedChildren = [];

        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('MicroBundle:Category')->findOneBy(['id' => $categoryId]);
        $hasParent = $category->getParent();
        $children = ($category) ? $category->getChildren() : null;

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $name = $category->getName();

            if ($children) {

                foreach ($children as $child) {

                    $serializedChildren[] = $this->container->get('serialize')->serlializeJson($child, 1, ['parameters']);
                }
            }

            $jsonData['id'] = $categoryId;
            $jsonData['name'] = $name;
            $jsonData['children'] = $serializedChildren;
            $jsonData['hasParent'] = $hasParent;

            return new JsonResponse($jsonData);
        }
    }

    /**
     * get product associated with parameter
     * @Method({"POST"})
     * @Route("/count-products/{parameterId}", name="category_count_products_ajax")
     * @param Request $request
     * @return JsonResponse
     */
    public function countProductsAction(Request $request, $parameterId)
    {
        $em = $this->getDoctrine()->getManager();

        $parameter = $em->getRepository('MicroBundle:Parameter')->findOneBy(['id' => $parameterId]);
        $productsCount = $parameter->getProductParameters()->count();

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $jsonData['productsCount'] = $productsCount;

            return new JsonResponse($jsonData);
        }
    }



}
