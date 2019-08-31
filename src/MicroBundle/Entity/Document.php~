<?php

namespace MicroBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Document
 *
 * @ORM\Table(name="document")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\DocumentRepository")
 */
class Document
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
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="scope", type="text")
     */
    private $scope;

    /**
     * @var string
     *
     * @ORM\Column(name="device_shortlist_position", type="text", nullable=true)
     */
    private $deviceShortlistPosition;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inspection_date", type="datetime")
     */
    private $inspectionDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="next_inspection_date", type="datetime", nullable=true)
     */
    private $nextInspectionDate;

    /**
     * @var int
     *
     * @ORM\Column(name="next_inspection_for_month", type="integer", nullable=true)
     */
    private $nextInspectionForMonth;


    /**
     * Many Documents have Many Inspectors.
     * @ORM\ManyToMany(targetEntity="Inspector")
     * @ORM\JoinTable(name="documents_inspectors",
     *      joinColumns={@ORM\JoinColumn(name="document_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="inspector_id", referencedColumnName="id")}
     *      )
     */
    private $inspectors;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="recomendations", type="text", nullable=true)
     */
    private $recomendations;

    /**
     * @var string
     *
     * @ORM\Column(name="legal", type="text")
     */
    private $legal;

    /**
     * @var string
     *
     * @ORM\Column(name="conclusion", type="text", nullable=true)
     */
    private $conclusion;


    /**
     * One Document has Many docDevices.
     * @ORM\OneToMany(targetEntity="DocDevice", mappedBy="document", cascade={"persist"})
     */
    private $docDevices;

    /**
     * One Document has Many DocPosition.
     * @ORM\OneToMany(targetEntity="DocPosition", mappedBy="document", cascade={"persist"})
     */
    private $docPositions;

    /**
     * Many Document have One Building.
     * @ORM\ManyToOne(targetEntity="Building", inversedBy="documents", cascade={"persist"})
     * @ORM\JoinColumn(name="building_id", referencedColumnName="id")
     */
    private $building;

    /**
     * One Document has One PdfSettings
     * @ORM\OneToOne(targetEntity="PdfSettings", inversedBy="document", cascade={"persist"})
     * @ORM\JoinColumn(name="pdf_settings_id", referencedColumnName="id")
     */
    private $pdfSettings;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->inspectors = new ArrayCollection();
        $this->docDevices = new ArrayCollection();
        $this->inspectionDate = new DateTime();
        $this->nextInspectionDate = new DateTime('now + 6 month');
        $this->pdfSettings = new PdfSettings($this);
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
     * @return Document
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
     * Set scope.
     *
     * @param string $scope
     *
     * @return Document
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope.
     *
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set deviceShortlistPosition.
     *
     * @param string|null $deviceShortlistPosition
     *
     * @return Document
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
     * Set inspectionDate.
     *
     * @param \DateTime $inspectionDate
     *
     * @return Document
     */
    public function setInspectionDate($inspectionDate)
    {
        $this->inspectionDate = $inspectionDate;

        return $this;
    }

    /**
     * Get inspectionDate.
     *
     * @return \DateTime
     */
    public function getInspectionDate()
    {
        return $this->inspectionDate;
    }

    /**
     * Set nextInspectionDate.
     *
     * @param \DateTime|null $nextInspectionDate
     *
     * @return Document
     */
    public function setNextInspectionDate($nextInspectionDate = null)
    {
        $this->nextInspectionDate = $nextInspectionDate;

        return $this;
    }

    /**
     * Get nextInspectionDate.
     *
     * @return \DateTime|null
     */
    public function getNextInspectionDate()
    {
        return $this->nextInspectionDate;
    }

    /**
     * Set nextInspectionForMonth.
     *
     * @param int|null $nextInspectionForMonth
     *
     * @return Document
     */
    public function setNextInspectionForMonth($nextInspectionForMonth = null)
    {
        $this->nextInspectionForMonth = $nextInspectionForMonth;

        return $this;
    }

    /**
     * Get nextInspectionForMonth.
     *
     * @return int|null
     */
    public function getNextInspectionForMonth()
    {
        return $this->nextInspectionForMonth;
    }

    /**
     * Set comment.
     *
     * @param string|null $comment
     *
     * @return Document
     */
    public function setComment($comment = null)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment.
     *
     * @return string|null
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set recomendations.
     *
     * @param string|null $recomendations
     *
     * @return Document
     */
    public function setRecomendations($recomendations = null)
    {
        $this->recomendations = $recomendations;

        return $this;
    }

    /**
     * Get recomendations.
     *
     * @return string|null
     */
    public function getRecomendations()
    {
        return $this->recomendations;
    }

    /**
     * Set legal.
     *
     * @param string $legal
     *
     * @return Document
     */
    public function setLegal($legal)
    {
        $this->legal = $legal;

        return $this;
    }

    /**
     * Get legal.
     *
     * @return string
     */
    public function getLegal()
    {
        return $this->legal;
    }

    /**
     * Set conclusion.
     *
     * @param string|null $conclusion
     *
     * @return Document
     */
    public function setConclusion($conclusion = null)
    {
        $this->conclusion = $conclusion;

        return $this;
    }

    /**
     * Get conclusion.
     *
     * @return string|null
     */
    public function getConclusion()
    {
        return $this->conclusion;
    }

    /**
     * Add inspector.
     *
     * @param \MicroBundle\Entity\Inspector $inspector
     *
     * @return Document
     */
    public function addInspector(\MicroBundle\Entity\Inspector $inspector)
    {
        $this->inspectors[] = $inspector;

        return $this;
    }

    /**
     * Remove inspector.
     *
     * @param \MicroBundle\Entity\Inspector $inspector
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeInspector(\MicroBundle\Entity\Inspector $inspector)
    {
        return $this->inspectors->removeElement($inspector);
    }

    /**
     * Get inspectors.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInspectors()
    {
        return $this->inspectors;
    }

    /**
     * Add docDevice.
     *
     * @param \MicroBundle\Entity\DocDevice $docDevice
     *
     * @return Document
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

    /**
     * Add docPosition.
     *
     * @param \MicroBundle\Entity\DocPosition $docPosition
     *
     * @return Document
     */
    public function addDocPosition(\MicroBundle\Entity\DocPosition $docPosition)
    {
        $this->docPositions[] = $docPosition;

        return $this;
    }

    /**
     * Remove docPosition.
     *
     * @param \MicroBundle\Entity\DocPosition $docPosition
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDocPosition(\MicroBundle\Entity\DocPosition $docPosition)
    {
        return $this->docPositions->removeElement($docPosition);
    }

    /**
     * Get docPositions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocPositions()
    {
        return $this->docPositions;
    }

    /**
     * Set building.
     *
     * @param \MicroBundle\Entity\Building|null $building
     *
     * @return Document
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
     * Set pdfSettings.
     *
     * @param \MicroBundle\Entity\PdfSettings|null $pdfSettings
     *
     * @return Document
     */
    public function setPdfSettings(\MicroBundle\Entity\PdfSettings $pdfSettings = null)
    {
        $this->pdfSettings = $pdfSettings;

        return $this;
    }

    /**
     * Get pdfSettings.
     *
     * @return \MicroBundle\Entity\PdfSettings|null
     */
    public function getPdfSettings()
    {
        return $this->pdfSettings;
    }
}
