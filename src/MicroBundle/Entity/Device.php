<?php

namespace MicroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table(name="device_name")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\DeviceNameRepository")
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
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
}
