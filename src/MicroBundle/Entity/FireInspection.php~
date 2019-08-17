<?php

namespace MicroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FireInspection
 *
 * @ORM\Table(name="fire_inspection")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\FireInspectionRepository")
 */
class FireInspection
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
     * @ORM\Column(name="scope", type="text")
     */
    private $scope;

    /**
     * @var string
     *
     * @ORM\Column(name="deviceShortlistPosition", type="text", nullable=true)
     */
    private $deviceShortlistPosition;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inspectionDate", type="datetime")
     */
    private $inspectionDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="nextInspectionDate", type="datetime", nullable=true)
     */
    private $nextInspectionDate;

    /**
     * @var int
     *
     * @ORM\Column(name="nextInspectionForMonth", type="integer", nullable=true)
     */
    private $nextInspectionForMonth;

    /**
     * One FireInspections has Many DocumentInspectors.
     * @ORM\OneToMany(targetEntity="DocumentInspector", mappedBy="fireInspection", cascade={"persist"})
     */
    private $documentInspectors;

    /**
     * Many FireInspectors have Many Inspectors.
     * @ORM\ManyToMany(targetEntity="Inspector")
     * @ORM\JoinTable(name="fire_inspection_temp_inspectors",
     *      joinColumns={@ORM\JoinColumn(name="fire_inspection_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="temp_inspectors_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $tempInspectors;

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
     * One Fire Inspection has Many inspectedDevices.
     * @ORM\OneToMany(targetEntity="MicroBundle\Entity\InspectedDevice", mappedBy="fireInspection", cascade={"persist"})
     */
    private $inspectedDevices;

    /**
     * One Fire Inspection has Many testPositions.
     * @ORM\OneToMany(targetEntity="MicroBundle\Entity\TestPosition", mappedBy="fireInspection", cascade={"persist"})
     */
    private $testPositions;

    /**
     * Many FireInspections have One Building.
     * @ORM\ManyToOne(targetEntity="Building", inversedBy="fireInspections", cascade={"persist"})
     * @ORM\JoinColumn(name="building_id", referencedColumnName="id")
     */
    private $building;

    /**
     * One FireInspection has One PdfSettings
     * @ORM\OneToOne(targetEntity="PdfSettings", inversedBy="fireInspection", cascade={"persist"})
     * @ORM\JoinColumn(name="pdf_settings_id", referencedColumnName="id")
     */
    private $pdfSettings;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->documentInspectors = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tempInspectors = new \Doctrine\Common\Collections\ArrayCollection();
        $this->inspectedDevices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->inspectionDate = new \DateTime();
        $this->nextInspectionDate = new \DateTime('now + 6 month');
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
     * Set scope.
     *
     * @param string $scope
     *
     * @return FireInspection
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
     * @return FireInspection
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
     * @return FireInspection
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
     * @param \DateTime $nextInspectionDate
     *
     * @return FireInspection
     */
    public function setNextInspectionDate($nextInspectionDate)
    {
        $this->nextInspectionDate = $nextInspectionDate;

        return $this;
    }

    /**
     * Get nextInspectionDate.
     *
     * @return \DateTime
     */
    public function getNextInspectionDate()
    {
        return $this->nextInspectionDate;
    }

    /**
     * Set comment.
     *
     * @param string|null $comment
     *
     * @return FireInspection
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
     * @return FireInspection
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
     * @return FireInspection
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
     * @return FireInspection
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
     * Add documentInspector.
     *
     * @param \MicroBundle\Entity\DocumentInspector $documentInspector
     *
     * @return FireInspection
     */
    public function addDocumentInspector(\MicroBundle\Entity\DocumentInspector $documentInspector)
    {
        $this->documentInspectors[] = $documentInspector;

        return $this;
    }

    /**
     * Remove documentInspector.
     *
     * @param \MicroBundle\Entity\DocumentInspector $documentInspector
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDocumentInspector(\MicroBundle\Entity\DocumentInspector $documentInspector)
    {
        return $this->documentInspectors->removeElement($documentInspector);
    }

    /**
     * Get documentInspectors.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocumentInspectors()
    {
        return $this->documentInspectors;
    }

    /**
     * Add tempInspector.
     *
     * @param \MicroBundle\Entity\Inspector $tempInspector
     *
     * @return FireInspection
     */
    public function addTempInspector(\MicroBundle\Entity\Inspector $tempInspector)
    {
        $this->tempInspectors[] = $tempInspector;

        return $this;
    }

    /**
     * Remove tempInspector.
     *
     * @param \MicroBundle\Entity\Inspector $tempInspector
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTempInspector(\MicroBundle\Entity\Inspector $tempInspector)
    {
        return $this->tempInspectors->removeElement($tempInspector);
    }

    /**
     * Get tempInspectors.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTempInspectors()
    {
        return $this->tempInspectors;
    }

    /**
     * Add inspectedDevice.
     *
     * @param \MicroBundle\Entity\InspectedDevice $inspectedDevice
     *
     * @return FireInspection
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

    /**
     * Set All inspectedDevices Visible.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function setInspectedDevicesVisible()
    {
        foreach ( $this->inspectedDevices as $inspectedDevice) {
            $inspectedDevice->setVisible(true);
        }

        return $this->inspectedDevices;
    }

    /**
     * Set building.
     *
     * @param \MicroBundle\Entity\Building|null $building
     *
     * @return FireInspection
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
     * Add testPosition.
     *
     * @param \MicroBundle\Entity\TestPosition $testPosition
     *
     * @return FireInspection
     */
    public function addTestPosition(\MicroBundle\Entity\TestPosition $testPosition)
    {
        $this->testPositions[] = $testPosition;

        return $this;
    }

    /**
     * Remove testPosition.
     *
     * @param \MicroBundle\Entity\TestPosition $testPosition
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTestPosition(\MicroBundle\Entity\TestPosition $testPosition)
    {
        return $this->testPositions->removeElement($testPosition);
    }

    /**
     * Get testPositions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTestPositions()
    {
        return $this->testPositions;
    }

    /**
     * Set nextInspectionForMonth.
     *
     * @param int $nextInspectionForMonth
     *
     * @return FireInspection
     */
    public function setNextInspectionForMonth($nextInspectionForMonth)
    {
        $this->nextInspectionForMonth = $nextInspectionForMonth;

        return $this;
    }

    /**
     * Get nextInspectionForMonth.
     *
     * @return int
     */
    public function getNextInspectionForMonth()
    {
        return $this->nextInspectionForMonth;
    }

    /**
     * Set pdfSettings.
     *
     * @param \MicroBundle\Entity\PdfSettings|null $pdfSettings
     *
     * @return FireInspection
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
