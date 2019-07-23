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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="shortname", type="string", length=5, nullable=true)
     */
    private $shortname;

    /**
     * @var int
     *
     * @ORM\Column(name="loopNo", type="integer", nullable=true)
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
     * Many FireProtectionDevices have One LoopDev.
     * @ORM\ManyToOne(targetEntity="LoopDev", inversedBy="fireProtectionDevices", cascade={"persist"})
     * @ORM\JoinColumn(name="loop_dev_id", referencedColumnName="id")
     */
    private $loopDev;

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
     * Remove all inspectedDevice.
     */

    public function removeAllInspectedDevices()
    {
        foreach ($this->inspectedDevices as $inspectedDevice) {
            $this->inspectedDevices->removeElement($inspectedDevice);
        }
        return $this;
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

    /**
     * Set loopDev.
     *
     * @param \MicroBundle\Entity\LoopDev|null $loopDev
     *
     * @return FireProtectionDevice
     */
    public function setLoopDev(\MicroBundle\Entity\LoopDev $loopDev = null)
    {
        $this->loopDev = $loopDev;

        return $this;
    }

    /**
     * Get loopDev.
     *
     * @return \MicroBundle\Entity\LoopDev|null
     */
    public function getLoopDev()
    {
        return $this->loopDev;
    }
}
