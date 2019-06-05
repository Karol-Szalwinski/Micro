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
     * @ORM\Column(name="deviceShortlistPosition", type="string", length=255)
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
     * @ORM\Column(name="nextInspectionDate", type="datetime")
     */
    private $nextInspectionDate;

    /**
     * @var string
     *
     * @ORM\Column(name="inspectors", type="string", length=255)
     */
    private $inspectors;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255)
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="recomendations", type="string", length=255)
     */
    private $recomendations;

    /**
     * @var string
     *
     * @ORM\Column(name="legal", type="string", length=255)
     */
    private $legal;

    /**
     * @var string
     *
     * @ORM\Column(name="conclusion", type="string", length=255)
     */
    private $conclusion;


    /**
     * @var string
     *
     * @ORM\Column(name="inspectedDevices", type="string", length=255)
     */
    private $inspectedDevices;

    /**
     * @var string
     *
     * @ORM\Column(name="otherActivities", type="string", length=255)
     */
    private $otherActivities;

    /**
     * Many FireInspections have One Building.
     * @ORM\ManyToOne(targetEntity="Building", inversedBy="fireInspections", cascade={"persist"})
     * @ORM\JoinColumn(name="building_id", referencedColumnName="id")
     */
    private $building;


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
     * @param string $deviceShortlistPosition
     *
     * @return FireInspection
     */
    public function setDeviceShortlistPosition($deviceShortlistPosition)
    {
        $this->deviceShortlistPosition = $deviceShortlistPosition;

        return $this;
    }

    /**
     * Get deviceShortlistPosition.
     *
     * @return string
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
     * Set inspectors.
     *
     * @param string $inspectors
     *
     * @return FireInspection
     */
    public function setInspectors($inspectors)
    {
        $this->inspectors = $inspectors;

        return $this;
    }

    /**
     * Get inspectors.
     *
     * @return string
     */
    public function getInspectors()
    {
        return $this->inspectors;
    }

    /**
     * Set comment.
     *
     * @param string $comment
     *
     * @return FireInspection
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set recomendations.
     *
     * @param string $recomendations
     *
     * @return FireInspection
     */
    public function setRecomendations($recomendations)
    {
        $this->recomendations = $recomendations;

        return $this;
    }

    /**
     * Get recomendations.
     *
     * @return string
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
     * Set inspectedDevices.
     *
     * @param string $inspectedDevices
     *
     * @return FireInspection
     */
    public function setInspectedDevices($inspectedDevices)
    {
        $this->inspectedDevices = $inspectedDevices;

        return $this;
    }

    /**
     * Get inspectedDevices.
     *
     * @return string
     */
    public function getInspectedDevices()
    {
        return $this->inspectedDevices;
    }

    /**
     * Set otherActivities.
     *
     * @param string $otherActivities
     *
     * @return FireInspection
     */
    public function setOtherActivities($otherActivities)
    {
        $this->otherActivities = $otherActivities;

        return $this;
    }

    /**
     * Get otherActivities.
     *
     * @return string
     */
    public function getOtherActivities()
    {
        return $this->otherActivities;
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
     * Set conclusion.
     *
     * @param string $conclusion
     *
     * @return FireInspection
     */
    public function setConclusion($conclusion)
    {
        $this->conclusion = $conclusion;

        return $this;
    }

    /**
     * Get conclusion.
     *
     * @return string
     */
    public function getConclusion()
    {
        return $this->conclusion;
    }
}
