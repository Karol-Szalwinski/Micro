<?php

namespace MicroBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank(message= "Nazwa nie może być pusta")
     * @Assert\Length(
     *      min = 3,
     *      max = 100,
     *      minMessage = "Nazwa musi zawierać co najmniej {{ limit }} znaki",
     *      maxMessage = "Nazwa nie może być dłuższa niż {{ limit }} znaków"
     * )
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * * @Assert\Length(
     *      min = 3,
     *      max = 40,
     *      maxMessage = "Ulica nie może być dłuższy niż {{ limit }} znaków",
     *      minMessage = "Ulica musi zawierać co najmniej {{ limit }} znaki",
     * )
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255)
     */
    private $street;

    /**
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Numer domu nie może być dłuższy niż {{ limit }} znaków",
     * )
     * @var string
     *
     * @ORM\Column(name="house_no", type="string", length=10)
     */
    private $houseNo;

    /**
     * @var string
     *
     * @ORM\Column(name="flat_no", type="string", length=10, nullable=true))
     */
    private $flatNo;

    /**
     * @Assert\Length(
     *      min = 3,
     *      max = 40,
     *      maxMessage = "Ulica nie może być dłuższy niż {{ limit }} znaków",
     *      minMessage = "Ulica musi zawierać co najmniej {{ limit }} znaki",
     * )
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
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
     * One Building has Many BuildDevice.
     * @ORM\OneToMany(targetEntity="BuildDevice", mappedBy="building", cascade={"persist"})
     */
    private $buildDevices;

    /**
     * One Building has Many FireInspections.
     * @ORM\OneToMany(targetEntity="Document", mappedBy="building", cascade={"persist"})
     */
    private $documents;

    /**
     * @var string
     *
     * @ORM\Column(name="deviceShortlistPosition", type="text", nullable=true)
     */
    private $deviceShortlistPosition;

    /**
     * @ORM\ManyToMany(targetEntity="PdfDocument")
     * @ORM\JoinTable(name="building_pdfs",
     *      joinColumns={@ORM\JoinColumn(name="building_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="pdf_id", referencedColumnName="id")}
     *      )
     */
    private $pdfDocuments;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->buildDevices = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->pdfDocuments = new ArrayCollection();
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
     * Add buildDevice.
     *
     * @param \MicroBundle\Entity\BuildDevice $buildDevice
     *
     * @return Building
     */
    public function addBuildDevice(\MicroBundle\Entity\BuildDevice $buildDevice)
    {
        $this->buildDevices[] = $buildDevice;

        return $this;
    }

    /**
     * Remove buildDevice.
     *
     * @param \MicroBundle\Entity\BuildDevice $buildDevice
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBuildDevice(\MicroBundle\Entity\BuildDevice $buildDevice)
    {
        return $this->buildDevices->removeElement($buildDevice);
    }

    /**
     * Get buildDevices.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBuildDevices()
    {
        return $this->buildDevices;
    }

    /**
     * Add document.
     *
     * @param \MicroBundle\Entity\Document $document
     *
     * @return Building
     */
    public function addDocument(\MicroBundle\Entity\Document $document)
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * Remove document.
     *
     * @param \MicroBundle\Entity\Document $document
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDocument(\MicroBundle\Entity\Document $document)
    {
        return $this->documents->removeElement($document);
    }

    /**
     * Get documents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Add pdfDocument.
     *
     * @param \MicroBundle\Entity\PdfDocument $pdfDocument
     *
     * @return Building
     */
    public function addPdfDocument(\MicroBundle\Entity\PdfDocument $pdfDocument)
    {
        $this->pdfDocuments[] = $pdfDocument;

        return $this;
    }

    /**
     * Remove pdfDocument.
     *
     * @param \MicroBundle\Entity\PdfDocument $pdfDocument
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePdfDocument(\MicroBundle\Entity\PdfDocument $pdfDocument)
    {
        return $this->pdfDocuments->removeElement($pdfDocument);
    }

    /**
     * Get pdfDocuments.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPdfDocuments()
    {
        return $this->pdfDocuments;
    }
}
