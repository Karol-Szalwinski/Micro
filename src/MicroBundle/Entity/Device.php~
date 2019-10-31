<?php

namespace MicroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Device
 *
 * @ORM\Table(name="device")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\DeviceRepository")
 */
class Device
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
     * @Assert\NotBlank(message= "Nazwa urządzenia nie może być pusta")
     * @Assert\Length(
     *      min = 3,
     *      max = 15,
     *      minMessage = "Nazwa musi zawierać co najmniej {{ limit }} znaki",
     *      maxMessage = "Nazwa nie może być dłuższa niż {{ limit }} znaków"
     * )
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @Assert\NotBlank(message= "Skrót urządzenia nie może być pusty")
     * @Assert\Length(
     *      min = 3,
     *      max = 5,
     *      minMessage = "Skrót musi zawierać co najmniej {{ limit }} znaki",
     *      maxMessage = "Skrót nie może być dłuższa niż {{ limit }} znaków"
     * )
     * @var string
     *
     * @ORM\Column(name="shortname", type="string", length=5, unique=true)
     */
    private $shortname;

    /**
     * @var bool
     *
     * @ORM\Column(name="in_building", type="boolean")
     */
    private $inBuilding;


    /**
     * @var bool
     *
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted;


    /**
     * Device constructor.
     */
    public function __construct()
    {
        $this->inBuilding = true;
        $this->deleted = false;
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
     * @return Device
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
     * @param string $shortname
     *
     * @return Device
     */
    public function setShortname($shortname)
    {
        $this->shortname = $shortname;

        return $this;
    }

    /**
     * Get shortname.
     *
     * @return string
     */
    public function getShortname()
    {
        return $this->shortname;
    }

    /**
     * Set inBuilding.
     *
     * @param bool $inBuilding
     *
     * @return Device
     */
    public function setInBuilding($inBuilding)
    {
        $this->inBuilding = $inBuilding;

        return $this;
    }

    /**
     * Get inBuilding.
     *
     * @return bool
     */
    public function getInBuilding()
    {
        return $this->inBuilding;
    }

    /**
     * Set deleted.
     *
     * @param bool $deleted
     *
     * @return Device
     */
    public function setDeleted($deleted = true)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted.
     *
     * @return bool
     */
    public function getDeleted()
    {
        return $this->deleted;
    }
}
