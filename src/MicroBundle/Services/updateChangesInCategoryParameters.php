<?php

namespace MicroBundle\Services;


use MicroBundle\Entity\Category;

class parametersService
{
    private $em;

    /**
     * BuildingUpdateService constructor.
     * @param $em
     */
    public function __construct($em)
    {
        $this->em = $em;
    }


    /**
     * @param Category $category
     * @param $originalParameters
     * @return Category
     */
    private function updateChangesInParameters(Category $category, $originalParameters): Category
    {
        $missingParameters = $this->checkDeletedParmeters($category, $originalParameters);
        $this->deleteMissingParametersFromCategory($category, $missingParameters);
        $categoryProductParameters  = $this->getAllProductParametersFromCategory($category);
        $this->deleteMissingParametersFromProducts($categoryProductParameters , $missingParameters);
        $changedParameters = $this->checkChangedParmeters($category, $originalParameters);

        return $category;

    }


    private function checkDeletedParmeters(Category $category, $originalParameters): array
    {
        $missingParameters = [];
        foreach ($originalParameters as $parameter) {
            if (false === $category->getParameters()->contains($parameter)) {
                $missingParameters[] = $parameter;
            }
        }
        return $missingParameters;
    }
    private function checkChangedParmeters(Category $category, $originalParameters): array
    {
        $changedParameters = [];
        foreach ($originalParameters as $parameter) {
            if (false === $category->getParameters()->contains($parameter)) {
                $missingParameters[] = $parameter;
            }
        }
        return $missingParameters;
    }


    private function deleteMissingParametersFromCategory(Category $category, $missingParameters): void
    {
        foreach ($missingParameters as $delParameter) {
            $category->removeParameter($delParameter);
            $delParameter->setCategory(null);
            $this->em->remove($delParameter);

        }
        $this->em->flush;
    }

    private function getAllProductParametersFromCategory(Category $category) : array
    {
        $categoryProductParameters = [];
        foreach ($category->getProducts() as $product) {
            foreach ($product->getProductParameters() as $productParameter) {
                $categoryProductParameters[] = $productParameter;
            }
        }
        return $categoryProductParameters;
    }

    private function deleteMissingParametersFromProducts($categoryProductParameters, $missingParameters): void
    {

        foreach ($categoryProductParameters as $productParameter) {
            foreach ($missingParameters as $parameter) {
                if($parameter->getName() == $productParameter->getName()) {
                    $productParameter->getProduct()->removeProductParameter($productParameter);
                    $productParameter->setProduct(null);
                    $this->em->remove($productParameter);
                }
            }
        }
    }

}