<?php

namespace MicroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FireProtectionDevice
 *
 * @ORM\Table(name="fire_protection_device")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\FireProtectionDeviceRepository")
 */
class FireProtectionDevice
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
     * @ORM\Column(name="shortname", type="string", length=5)
     */
    private $shortname;

    /**
     * @var int
     *
     * @ORM\Column(name="loopNo", type="integer")
     */
    private $loopNo;

    /**
     * @var int
     *
     * @ORM\Column(name="number", type="integer")
     */
    private $number;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="lastServiceDate", type="datetime", nullable=true)
     */
    private $lastServiceDate;

    /**
     * Many FireProtectionDevices have One Building.
     * @ORM\ManyToOne(targetEntity="Building", inversedBy="fireProtectionDevices", cascade={"persist"})
     * @ORM\JoinColumn(name="building_id", referencedColumnName="id")
     */
    private $building;

    /**
     * One FireProtectionDevices has Many inspectedDevices.
     * @ORM\OneToMany(targetEntity="MicroBundle\Entity\InspectedDevice", mappedBy="fireProtectionDevice")
     */
    private $inspectedDevices;


   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->inspectedDevices = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set id.
     *
     * @param $id
     *
     * @return FireProtectionDevice
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return FireProtectionDevice
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
     * @return FireProtectionDevice
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
     * Set loopNo.
     *
     * @param int $loopNo
     *
     * @return FireProtectionDevice
     */
    public function setLoopNo($loopNo)
    {
        $this->loopNo = $loopNo;

        return $this;
    }

    /**
     * Get loopNo.
     *
     * @return int
     */
    public function getLoopNo()
    {
        return $this->loopNo;
    }

    /**
     * Set number.
     *
     * @param int $number
     *
     * @return FireProtectionDevice
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number.
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set lastServiceDate.
     *
     * @param \DateTime|null $lastServiceDate
     *
     * @return FireProtectionDevice
     */
    public function setLastServiceDate($lastServiceDate = null)
    {
        $this->lastServiceDate = $lastServiceDate;

        return $this;
    }

    /**
     * Get lastServiceDate.
     *
     * @return \DateTime|null
     */
    public function getLastServiceDate()
    {
        return $this->lastServiceDate;
    }

    /**
     * Set building.
     *
     * @param \MicroBundle\Entity\Building|null $building
     *
     * @return FireProtectionDevice
     */
    public function setBuilding(\MicroBundle\Entity\Building $building = null)
    {
        $this->building = $building;

        return $this;
    }

    /**
     * Get building.
     *
     * @return \MicroBundle\Entity\Building|null
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * Add inspectedDevice.
     *
     * @param \MicroBundle\Entity\InspectedDevice $inspectedDevice
     *
     * @return FireProtectionDevice
     */
    public function addInspectedDevice(\MicroBundle\Entity\InspectedDevice $inspectedDevice)
    {
        $this->inspectedDevices[] = $inspectedDevice;

        return $this;
    }

    /**
     * Remove inspectedDevice.
     *
     * @param \MicroBundle\Entity\InspectedDevice $inspectedDevice
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeInspectedDevice(\MicroBundle\Entity\InspectedDevice $inspectedDevice)
    {
        return $this->inspectedDevices->removeElement($inspectedDevice);
    }

    /**
     * Get inspectedDevices.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInspectedDevices()
    {
        return $this->inspectedDevices;
    }
}
