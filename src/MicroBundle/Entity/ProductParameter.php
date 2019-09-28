<?php

namespace MicroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MicroBundle\Entity\Category;
use MicroBundle\Entity\Parameter;
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
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="Parameter", inversedBy="productParameters")
     * @ORM\JoinColumn(name="parameter_id", referencedColumnName="id", onDelete="cascade")
     */
    private $parameter;

    /**
     * @var int
     *
     * @ORM\Column(name="prototypeId", type="integer", nullable=true)
     */
    private $prototypeId;

    /**
     * ProductParameter constructor.
     * @param string $name
     * @param $parameter
     */
    public function __construct($name = "", $parameter = null)
    {
        $this->name = $name;
        $this->parameter = $parameter;
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

    /**
     * Set parameter.
     *
     * @param Parameter|null $parameter
     *
     * @return ProductParameter
     */
    public function setParameter(Parameter $parameter = null)
    {
        $this->parameter = $parameter;

        return $this;
    }

    /**
     * Get parameter.
     *
     * @return Parameter|null
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * Set prototypeId.
     *
     * @param int|null $prototypeId
     *
     * @return ProductParameter
     */
    public function setPrototypeId($prototypeId = null)
    {
        $this->prototypeId = $prototypeId;

        return $this;
    }

    /**
     * Get prototypeId.
     *
     * @return int|null
     */
    public function getPrototypeId()
    {
        return $this->prototypeId;
    }
}
