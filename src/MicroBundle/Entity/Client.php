<?php

namespace MicroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\ClientRepository")
 */
class Client
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
     * @ORM\Column(name="shortname", type="string", length=100, nullable=true)
     */
    private $shortname;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="addDate", type="datetime", nullable=true)
     */
    private $addDate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="buidings", type="string", length=255, nullable=true)
     */
    private $buidings;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->addDate = new \DateTime();
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
     * @param string $name
     *
     * @return Client
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
     * Set shortname.
     *
     * @param string|null $shortname
     *
     * @return Client
     */
    public function setShortname($shortname = null)
    {
        $this->shortname = $shortname;

        return $this;
    }

    /**
     * Get shortname.
     *
     * @return string|null
     */
    public function getShortname()
    {
        return $this->shortname;
    }

    /**
     * Set addDate.
     *
     * @param \DateTime|null $addDate
     *
     * @return Client
     */
    public function setAddDate($addDate = null)
    {
        $this->addDate = $addDate;

        return $this;
    }

    /**
     * Get addDate.
     *
     * @return \DateTime|null
     */
    public function getAddDate()
    {
        return $this->addDate;
    }

    /**
     * Set buidings.
     *
     * @param string|null $buidings
     *
     * @return Client
     */
    public function setBuidings($buidings = null)
    {
        $this->buidings = $buidings;

        return $this;
    }

    /**
     * Get buidings.
     *
     * @return string|null
     */
    public function getBuidings()
    {
        return $this->buidings;
    }
}
