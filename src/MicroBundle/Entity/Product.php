<?php

namespace MicroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MicroBundle\Entity\ProductParameter;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\ProductRepository")
 */
class Product
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=100, nullable=true)
     */
    private $model;

    /**
     * @var string
     *
     * @ORM\Column(name="producent", type="string", length=100, nullable=true)
     */
    private $producent;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="ProductParameter", mappedBy="product",  cascade={"persist"})
     */
    private $productParameters;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", nullable=true)
     */
    private $image;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set model.
     *
     * @param string $model
     *
     * @return Product
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set producent.
     *
     * @param string $producent
     *
     * @return Product
     */
    public function setProducent($producent)
    {
        $this->producent = $producent;

        return $this;
    }

    /**
     * Get producent.
     *
     * @return string
     */
    public function getProducent()
    {
        return $this->producent;
    }

    /**
     * Set price.
     *
     * @param int $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set category.
     *
     * @param string $category
     *
     * @return Product
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }


    /**
     * Set productParameters.
     *
     * @param string|null $productParameters
     *
     * @return Product
     */
    public function setProductParameters($productParameters = null)
    {
        $this->productParameters = $productParameters;

        return $this;
    }

    /**
     * Get productParameters.
     *
     * @return string|null
     */
    public function getProductParameters()
    {
        return $this->productParameters;
    }


    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return Product
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productParameters = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add productParameter.
     *
     * @param ProductParameter $productParameter
     *
     * @return Product
     */
    public function addProductParameter(ProductParameter $productParameter)
    {
        $productParameter->setProduct($this);
        $this->productParameters[] = $productParameter;

        return $this;
    }

    /**
     * Remove productParameter.
     *
     * @param ProductParameter $productParameter
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductParameter(ProductParameter $productParameter)
    {
        return $this->productParameters->removeElement($productParameter);
    }

    /**
     * Set image.
     *
     * @param string|null $image
     *
     * @return Product
     */
    public function setImage($image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return string|null
     */
    public function getImage()
    {
        return $this->image;
    }
}
