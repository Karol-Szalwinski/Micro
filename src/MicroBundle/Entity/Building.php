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
     * One Building has Many LoopDev.
     * @ORM\OneToMany(targetEntity="LoopDev", mappedBy="building", cascade={"persist"})
     */
    private $loopDevs;

    /**
     * One Building has Many FireInspections.
     * @ORM\OneToMany(targetEntity="FireInspection", mappedBy="building", cascade={"persist"})
     */
    private $fireInspections;

    /**
     * @var string
     *
     * @ORM\Column(name="deviceShortlistPosition", type="text", nullable=true)
     */
    private $deviceShortlistPosition;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->loopDevs = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Add loopDev.
     *
     * @param \MicroBundle\Entity\LoopDev $loopDev
     *
     * @return Building
     */
    public function addLoopDev(\MicroBundle\Entity\LoopDev $loopDev)
    {
        $this->loopDevs[] = $loopDev;

        return $this;
    }

    /**
     * Remove loopDev.
     *
     * @param \MicroBundle\Entity\LoopDev $loopDev
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeLoopDev(\MicroBundle\Entity\LoopDev $loopDev)
    {
        return $this->loopDevs->removeElement($loopDev);
    }

    /**
     * Get loopDevs.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLoopDevs()
    {
        return $this->loopDevs;
    }

    /**
     * Set deviceShortlistPosition.
     *
     * @param string|null $deviceShortlistPosition
     *
     * @return Building
     */
    public function setDeviceShortlistPosition($deviceShortlistPosition = null)
    {
        $this->deviceShortlistPosition = $deviceShortlistPosition;

        return $this;
    }

    /**
     * Get deviceShortlistPosition.
     *
     * @return string|null
     */
    public function getDeviceShortlistPosition()
    {
        return $this->deviceShortlistPosition;
    }
}
