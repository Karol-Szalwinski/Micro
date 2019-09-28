<?php

namespace MicroBundle\Services;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use MicroBundle\Entity\Category;
use MicroBundle\Entity\Product;
use MicroBundle\Entity\ProductParameter;

class ParametersService
{
    private $em;

    /**
     * BuildingUpdateService constructor.
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function connectNewProductParameterWithParent($product): Product
    {

        foreach ($product->getProductParameters() as $productParameter) {
            if ($productParameter->getParameter() == null) {
                $parameter = $this->em->getRepository('MicroBundle:Parameter')->findOneBy(['id' => $productParameter->getPrototypeId()]);
                if ($parameter) {
                    $parameter->addProductParameter($productParameter);
                    $productParameter->setParameter($parameter);
                }
            }
        }
        return $product;

    }

    /**
     * @param Category $category
     * @param $originalParameters
     * @return Category
     */
    public function handleChangesInParameters(Category $category, $originalParameters): Category
    {
        //remove deleted parameters
        foreach ($originalParameters as $parameter) {
            if (false === $category->getParameters()->contains($parameter)) {
                $category->removeParameter($parameter);
                $parameter->setCategory(null);
                $this->em->remove($parameter);
            }
        }

        //change names

        foreach ($originalParameters as $parameter) {
            if (true === $category->getParameters()->contains($parameter)) {
                foreach ($parameter->getProductParameters() as $productParameter) {
                    $productParameter->setName($parameter->getName());
                }
            }
        }


        return $category;

    }

    public function updateProductParameters($product): Product
    {

        $originalParameters = $product->getCategory()->getParameters();
        $currentParameters = new ArrayCollection();
        foreach ($product->getProductParameters() as $productParameter) {

            $currentParameters[] = $productParameter->getParameter();
        }

        foreach ($originalParameters as $parameter) {
            if (false === $currentParameters->contains($parameter)) {
                $product->addProductParameter(new ProductParameter($parameter->getName(), $parameter));
            }
        }

        return $product;
    }


}