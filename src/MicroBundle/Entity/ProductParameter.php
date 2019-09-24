<?php

namespace MicroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MicroBundle\Entity\Category;
use MicroBundle\Entity\Product;

/**
 * Parameter
 *
 * @ORM\Table(name="product_parameter")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\ProductParameterRepository")
 */
class ProductParameter
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
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;
    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=100)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="productParameters")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;



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
     * @param string $id
     *
     * @return ProductParameter
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;

    }/**
     * Set name.
     *
     * @param string $name
     *
     * @return ProductParameter
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
     * Set value.
     *
     * @param string $value
     *
     * @return ProductParameter
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set product.
     *
     * @param Product|null $product
     *
     * @return ProductParameter
     */
    public function setProduct(Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product.
     *
     * @return Product|null
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Add category.
     *
     * @return Product|null
     */
    public function addProduct(Product $product)
    {
        return $this->product;
    }
}
