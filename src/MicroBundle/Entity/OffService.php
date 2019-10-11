<?php

namespace MicroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OffService
 *
 * @ORM\Table(name="off_services")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\OffServicesRepository")
 */
class OffService
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
     * @ORM\Column(name="name", type="string", length=150)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="taxrate", type="string", length=255, nullable=true)
     */
    private $taxrate;

    /**
     * Many offServices have one offert. This is the owning side.
     * @ORM\ManyToOne(targetEntity="Offert", inversedBy="offServices")
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
     * @return OffService
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
     * @param int $amount
     *
     * @return OffService
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount.
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set price.
     *
     * @param int $price
     *
     * @return OffService
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
     * Set taxrate.
     *
     * @param string $taxrate
     *
     * @return OffService
     */
    public function setTaxrate($taxrate)
    {
        $this->taxrate = $taxrate;

        return $this;
    }

    /**
     * Get taxrate.
     *
     * @return string
     */
    public function getTaxrate()
    {
        return $this->taxrate;
    }

    /**
     * Set offert.
     *
     * @param string $offert
     *
     * @return OffService
     */
    public function setOffert($offert)
    {
        $this->offert = $offert;

        return $this;
    }

    /**
     * Get offert.
     *
     * @return string
     */
    public function getOffert()
    {
        return $this->offert;
    }
}
