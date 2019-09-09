<?php

namespace MicroBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * BuildDevice
 *
 * @ORM\Table(name="build_device")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\BuildDeviceRepository")
 */
class BuildDevice
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
     * @ORM\Column(name="loop_no", type="integer", nullable=true)
     */
    private $loopNo;

    /**
     * @var int
     *
     * @ORM\Column(name="number", type="integer")
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="serial", type="string", length=100, nullable=true)
     */
    private $serial;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=100, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $desc;

    /**
     * @var bool
     *
     * @ORM\Column(name="del", type="boolean")
     */
    private $del;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_service_date", type="datetime", nullable=true)
     */
    private $lastServiceDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="temp_service_date", type="datetime", nullable=true)
     */
    private $tempServiceDate;

    /**
     * Many BuildDevice have One Building.
     * @ORM\ManyToOne(targetEntity="Building", inversedBy="buildDevices", cascade={"persist"})
     * @ORM\JoinColumn(name="building_id", referencedColumnName="id")
     */
    private $building;

    /**
     * One BuildDevice has Many docDevices.
     * @ORM\OneToMany(targetEntity="DocDevice", mappedBy="buildDevice")
     */
    private $docDevices;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->docDevice = new ArrayCollection();
        $this->del = false;
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
     * @param string|null $name
     *
     * @return BuildDevice
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
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
     * @return BuildDevice
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
     * Set loopNo.
     *
     * @param int|null $loopNo
     *
     * @return BuildDevice
     */
    public function setLoopNo($loopNo = null)
    {
        $this->loopNo = $loopNo;

        return $this;
    }

    /**
     * Get loopNo.
     *
     * @return int|null
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
     * @return BuildDevice
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
     * Set serial.
     *
     * @param string|null $serial
     *
     * @return BuildDevice
     */
    public function setSerial($serial = null)
    {
        $this->serial = $serial;

        return $this;
    }

    /**
     * Get serial.
     *
     * @return string|null
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Set address.
     *
     * @param string|null $address
     *
     * @return BuildDevice
     */
    public function setAddress($address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string|null
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set desc.
     *
     * @param string|null $desc
     *
     * @return BuildDevice
     */
    public function setDesc($desc = null)
    {
        $this->desc = $desc;

        return $this;
    }

    /**
     * Get desc.
     *
     * @return string|null
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Set del.
     *
     * @param bool $del
     *
     * @return BuildDevice
     */
    public function setDel($del)
    {
        $this->del = $del;

        return $this;
    }

    /**
     * Get del.
     *
     * @return bool
     */
    public function getDel()
    {
        return $this->del;
    }

    /**
     * Set lastServiceDate.
     *
     * @param \DateTime|null $lastServiceDate
     *
     * @return BuildDevice
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
     * Set tempServiceDate.
     *
     * @param \DateTime|null $tempServiceDate
     *
     * @return BuildDevice
     */
    public function setTempServiceDate($tempServiceDate = null)
    {
        $this->tempServiceDate = $tempServiceDate;

        return $this;
    }

    /**
     * Get tempServiceDate.
     *
     * @return \DateTime|null
     */
    public function getTempServiceDate()
    {
        return $this->tempServiceDate;
    }

    /**
     * Set building.
     *
     * @param \MicroBundle\Entity\Building|null $building
     *
     * @return BuildDevice
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
     * Add docDevice.
     *
     * @param \MicroBundle\Entity\DocDevice $docDevice
     *
     * @return BuildDevice
     */
    public function addDocDevice(\MicroBundle\Entity\DocDevice $docDevice)
    {
        $this->docDevices[] = $docDevice;

        return $this;
    }

    /**
     * Remove docDevice.
     *
     * @param \MicroBundle\Entity\DocDevice $docDevice
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDocDevice(\MicroBundle\Entity\DocDevice $docDevice)
    {
        return $this->docDevices->removeElement($docDevice);
    }

    /**
     * Get docDevices.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocDevices()
    {
        return $this->docDevices;
    }

}
