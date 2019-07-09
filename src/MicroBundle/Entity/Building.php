<?php

namespace MicroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Building
 *
 * @ORM\Table(name="building")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\BuildingRepository")
 */
class Building
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
     * @ORM\Column(name="street", type="string", length=255)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="house_no", type="string", length=255)
     */
    private $houseNo;

    /**
     * @var string
     *
     * @ORM\Column(name="flat_no", type="string", length=255, nullable=true))
     */
    private $flatNo;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="postal_code", type="string", length=255)
     */
    private $postalCode;


    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * Many Buildings have One Client.
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="buildings", cascade={"persist"})
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * One Building has Many FireProtectionDevices.
     * @ORM\OneToMany(targetEntity="FireProtectionDevice", mappedBy="building", cascade={"persist"})
     */
    private $fireProtectionDevices;

    /**
     * One Building has Many FireInspections.
     * @ORM\OneToMany(targetEntity="FireInspection", mappedBy="building", cascade={"persist"})
     */
    private $fireInspections;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fireProtectionDevices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fireInspections = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Building
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
     * Set street.
     *
     * @param string $street
     *
     * @return Building
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street.
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set houseNo.
     *
     * @param string $houseNo
     *
     * @return Building
     */
    public function setHouseNo($houseNo)
    {
        $this->houseNo = $houseNo;

        return $this;
    }

    /**
     * Get houseNo.
     *
     * @return string
     */
    public function getHouseNo()
    {
        return $this->houseNo;
    }

    /**
     * Set flatNo.
     *
     * @param string|null $flatNo
     *
     * @return Building
     */
    public function setFlatNo($flatNo = null)
    {
        $this->flatNo = $flatNo;

        return $this;
    }

    /**
     * Get flatNo.
     *
     * @return string|null
     */
    public function getFlatNo()
    {
        return $this->flatNo;
    }

    /**
     * Set city.
     *
     * @param string $city
     *
     * @return Building
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set postalCode.
     *
     * @param string $postalCode
     *
     * @return Building
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode.
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return Building
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set client.
     *
     * @param \MicroBundle\Entity\Client|null $client
     *
     * @return Building
     */
    public function setClient(\MicroBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client.
     *
     * @return \MicroBundle\Entity\Client|null
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Add fireProtectionDevice.
     *
     * @param \MicroBundle\Entity\FireProtectionDevice $fireProtectionDevice
     *
     * @return Building
     */
    public function addFireProtectionDevice(\MicroBundle\Entity\FireProtectionDevice $fireProtectionDevice)
    {
        $this->fireProtectionDevices[] = $fireProtectionDevice;

        return $this;
    }

    /**
     * Remove fireProtectionDevice.
     *
     * @param \MicroBundle\Entity\FireProtectionDevice $fireProtectionDevice
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeFireProtectionDevice(\MicroBundle\Entity\FireProtectionDevice $fireProtectionDevice)
    {
        return $this->fireProtectionDevices->removeElement($fireProtectionDevice);
    }

    /**
     * Get fireProtectionDevices.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFireProtectionDevices()
    {
        return $this->fireProtectionDevices;
    }

    /**
     * Add fireInspection.
     *
     * @param \MicroBundle\Entity\FireInspection $fireInspection
     *
     * @return Building
     */
    public function addFireInspection(\MicroBundle\Entity\FireInspection $fireInspection)
    {
        $this->fireInspections[] = $fireInspection;

        return $this;
    }

    /**
     * Remove fireInspection.
     *
     * @param \MicroBundle\Entity\FireInspection $fireInspection
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeFireInspection(\MicroBundle\Entity\FireInspection $fireInspection)
    {
        return $this->fireInspections->removeElement($fireInspection);
    }

    /**
     * Get fireInspections.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFireInspections()
    {
        return $this->fireInspections;
    }
}
