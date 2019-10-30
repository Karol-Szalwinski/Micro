<?php

namespace MicroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank(message= "Nazwa nie może być pusta")
     * @Assert\Length(
     *      min = 3,
     *      max = 100,
     *      minMessage = "Nazwa musi zawierać co najmniej {{ limit }} znaki",
     *      maxMessage = "Nazwa nie może być dłuższa niż {{ limit }} znaków"
     * )
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @Assert\Length(
     *      min = 3,
     *      max = 30,
     *      maxMessage = "Skrót nie może być dłuższy niż {{ limit }} znaków",
     *      minMessage = "Skrót musi zawierać co najmniej {{ limit }} znaki",
     * )
     * @var string|null
     *
     * @ORM\Column(name="shortname", type="string", length=30, nullable=true)
     */
    private $shortname;

    /**
     * @Assert\Email(
     *     message = "Adres email '{{ value }}' nie jest poprawny",
     *     checkMX = true
     * )
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @Assert\Regex(
     *     pattern     = "/^(?:\(?\+?48)?(?:[-\.\(\)\s]*(\d)){9}\)?$/",
     *     htmlPattern = "/^(?:\(?\+?48)?(?:[-\.\(\)\s]*(\d)){9}\)?$/",
     *     message = "Wprowadź poprawny polski nr telefonu"
     * )
     * @var string|null
     *
     * @ORM\Column(name="phone_number", type="string", length=20, nullable=true)
     */
    private $phoneNumber;

    /**
     * @Assert\Length(
     *      min = 3,
     *      max = 40,
     *      maxMessage = "Ulica nie może być dłuższy niż {{ limit }} znaków",
     *      minMessage = "Ulica musi zawierać co najmniej {{ limit }} znaki",
     * )
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255, nullable=true)
     */
    private $street;

    /**
     *
     * @Assert\Length(
     *      max = 5,
     *      maxMessage = "Numer domu nie może być dłuższy niż {{ limit }} znaków",
     * )
     * @var string
     *
     * @ORM\Column(name="house_no", type="string", length=5, nullable=true)
     */
    private $houseNo;

    /**
     * @Assert\Length(
     *      max = 5,
     *      maxMessage = "Numer lokalu nie może być dłuższy niż {{ limit }} znaków",
     * )
     *
     * @var string
     *
     * @ORM\Column(name="flat_no", type="string", length=5, nullable=true)
     */
    private $flatNo;

    /**
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Miasto nie może być dłuższy niż {{ limit }} znaków",
     * )
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=100, nullable=true)
     */
    private $city;

    /**
     * @Assert\Regex(
     *     pattern     = "/\d{2}-\d{3}/",
     *     htmlPattern = "/\d{2}-\d{3}/",
     *     message = "Wprowadź poprawny kod pocztowy w formacie 00-000"
     * )
     * @var string
     *
     * @ORM\Column(name="postal_code", type="string", length=255, nullable=true)
     */
    private $postalCode;


    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="add_date", type="datetime", nullable=true)
     */
    private $addDate;

    /**
     * One Client has Many Building.
     * @ORM\OneToMany(targetEntity="Building", mappedBy="client", cascade={"persist"})
     */
    private $buildings;

    /**
     * @ORM\ManyToMany(targetEntity="Offert", mappedBy="clients")
     */
    private $offerts;


    

    /**
     * Constructor
     */
    public function __construct($name = "")
    {
        $this->name = $name;
        $this->buildings = new ArrayCollection();
        $this->offerts = new ArrayCollection();
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
     * Set id.
     *
     * @param int $id
     *
     * @return Client
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
     * Set email.
     *
     * @param string|null $email
     *
     * @return Client
     */
    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phoneNumber.
     *
     * @param string|null $phoneNumber
     *
     * @return Client
     */
    public function setPhoneNumber($phoneNumber = null)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber.
     *
     * @return string|null
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
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
     * Add building.
     *
     * @param \MicroBundle\Entity\Building $building
     *
     * @return Client
     */
    public function addBuilding(\MicroBundle\Entity\Building $building)
    {
        $this->buildings[] = $building;

        return $this;
    }

    /**
     * Remove building.
     *
     * @param \MicroBundle\Entity\Building $building
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBuilding(\MicroBundle\Entity\Building $building)
    {
        return $this->buildings->removeElement($building);
    }

    /**
     * Get buildings.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBuildings()
    {
        return $this->buildings;
    }

    /**
     * Set street.
     *
     * @param string|null $street
     *
     * @return Client
     */
    public function setStreet($street = null)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street.
     *
     * @return string|null
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set houseNo.
     *
     * @param string|null $houseNo
     *
     * @return Client
     */
    public function setHouseNo($houseNo = null)
    {
        $this->houseNo = $houseNo;

        return $this;
    }

    /**
     * Get houseNo.
     *
     * @return string|null
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
     * @return Client
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
     * @param string|null $city
     *
     * @return Client
     */
    public function setCity($city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return string|null
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set postalCode.
     *
     * @param string|null $postalCode
     *
     * @return Client
     */
    public function setPostalCode($postalCode = null)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode.
     *
     * @return string|null
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Add offert.
     *
     * @param \MicroBundle\Entity\Offert $offert
     *
     * @return Client
     */
    public function addOffert(\MicroBundle\Entity\Offert $offert)
    {
        $this->offerts[] = $offert;

        return $this;
    }

    /**
     * Remove offert.
     *
     * @param \MicroBundle\Entity\Offert $offert
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeOffert(\MicroBundle\Entity\Offert $offert)
    {
        return $this->offerts->removeElement($offert);
    }

    /**
     * Get offerts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOfferts()
    {
        return $this->offerts;
    }
}
