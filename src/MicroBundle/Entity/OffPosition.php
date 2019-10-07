<?php

namespace MicroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OffPosition
 *
 * @ORM\Table(name="off_position")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\OffPositionRepository")
 */
class OffPosition
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
     * @var string|null
     *
     * @ORM\Column(name="amount", type="string", length=255, nullable=true)
     */
    private $amount;

    /**
     * @var int|null
     *
     * @ORM\Column(name="price", type="integer", nullable=true)
     */
    private $price;

    /**
     * @var int|null
     *
     * @ORM\Column(name="taxrate", type="integer", nullable=true)
     */
    private $taxrate;

    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * Many offPositions have one offert. This is the owning side.
     * @ORM\ManyToOne(targetEntity="Offert", inversedBy="offPositions")
     * @ORM\JoinColumn(name="offert_id", referencedColumnName="id")
     */
    private $offert;


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
     * @return OffPosition
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
     * Set amount.
     *
     * @param string|null $amount
     *
     * @return OffPosition
     */
    public function setAmount($amount = null)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount.
     *
     * @return string|null
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set price.
     *
     * @param int|null $price
     *
     * @return OffPosition
     */
    public function setPrice($price = null)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return int|null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set taxrate.
     *
     * @param int|null $taxrate
     *
     * @return OffPosition
     */
    public function setTaxrate($taxrate = null)
    {
        $this->taxrate = $taxrate;

        return $this;
    }

    /**
     * Get taxrate.
     *
     * @return int|null
     */
    public function getTaxrate()
    {
        return $this->taxrate;
    }

    /**
     * Set product.
     *
     * @param string|null $product
     *
     * @return OffPosition
     */
    public function setProduct($product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product.
     *
     * @return string|null
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set offert.
     *
     * @param \MicroBundle\Entity\Offert|null $offert
     *
     * @return OffPosition
     */
    public function setOffert(\MicroBundle\Entity\Offert $offert = null)
    {
        $this->offert = $offert;

        return $this;
    }

    /**
     * Get offert.
     *
     * @return \MicroBundle\Entity\Offert|null
     */
    public function getOffert()
    {
        return $this->offert;
    }
}
