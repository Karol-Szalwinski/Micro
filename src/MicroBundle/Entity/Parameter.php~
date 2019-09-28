<?php

namespace MicroBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use MicroBundle\Entity\Category;

/**
 * Parameter
 *
 * @ORM\Table(name="parameter")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\ParameterRepository")
 */
class Parameter
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="parameters")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;


    /**
     * One parameter has many productParameters. This is the inverse side.
     * @ORM\OneToMany(targetEntity="ProductParameter", mappedBy="parameter")
     */
    private $productParameters;

    public function __construct() {
        $this->productParameters = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set category.
     *
     * @param Category|null $category
     *
     * @return Parameter
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return Category|null
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * Add category.
     *
     * @return Category|null
     */
    public function addCategory(Category $category)
    {
        return $this->category;
    }

    /**
     * Add productParameter.
     *
     * @param \MicroBundle\Entity\ProductParameter $productParameter
     *
     * @return Parameter
     */
    public function addProductParameter(\MicroBundle\Entity\ProductParameter $productParameter)
    {
        $this->productParameters[] = $productParameter;

        return $this;
    }

    /**
     * Remove productParameter.
     *
     * @param \MicroBundle\Entity\ProductParameter $productParameter
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductParameter(\MicroBundle\Entity\ProductParameter $productParameter)
    {
        return $this->productParameters->removeElement($productParameter);
    }

    /**
     * Get productParameters.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductParameters()
    {
        return $this->productParameters;
    }
}
