<?php

namespace MicroBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * PdfSettings
 *
 * @ORM\Table(name="pdf_settings")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\PdfSettingsRepository")
 */
class PdfSettings
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
     * @var bool
     *
     * @ORM\Column(name="showTables", type="boolean")
     */
    private $showTables;

    /**
     * @var bool
     *
     * @ORM\Column(name="showBuildingData", type="boolean")
     */
    private $showBuildingData;

    /**
     * @var bool
     *
     * @ORM\Column(name="showClientData", type="boolean")
     */
    private $showClientData;

    /**
     * @var bool
     *
     * @ORM\Column(name="showStamp", type="boolean")
     */
    private $showStamp;

    /**
     * @var string
     *
     * @ORM\Column(name="inspectors", type="array")
     */
    private $inspectors;

    /**
     * One PdfSettings has One FireInspection.
     * @ORM\OneToOne(targetEntity="FireInspection", mappedBy="pdfSettings")
     */
    private $fireInspection;

    /**
     * PdfSettings constructor.
     */
    public function __construct($fireInspection)
    {
        $this->fireInspection = $fireInspection;
        $this->showTables = true;
        $this->showBuildingData = true;
        $this->showClientData = false;
        $this->showStamp = false;

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
     * Set showTables.
     *
     * @param bool $showTables
     *
     * @return PdfSettings
     */
    public function setShowTables($showTables)
    {
        $this->showTables = $showTables;

        return $this;
    }

    /**
     * Get showTables.
     *
     * @return bool
     */
    public function getShowTables()
    {
        return $this->showTables;
    }

    /**
     * Set showBuildingData.
     *
     * @param bool $showBuildingData
     *
     * @return PdfSettings
     */
    public function setShowBuildingData($showBuildingData)
    {
        $this->showBuildingData = $showBuildingData;

        return $this;
    }

    /**
     * Get showBuildingData.
     *
     * @return bool
     */
    public function getShowBuildingData()
    {
        return $this->showBuildingData;
    }

    /**
     * Set showClientData.
     *
     * @param bool $showClientData
     *
     * @return PdfSettings
     */
    public function setShowClientData($showClientData)
    {
        $this->showClientData = $showClientData;

        return $this;
    }

    /**
     * Get showClientData.
     *
     * @return bool
     */
    public function getShowClientData()
    {
        return $this->showClientData;
    }

    /**
     * Set showStamp.
     *
     * @param bool $showStamp
     *
     * @return PdfSettings
     */
    public function setShowStamp($showStamp)
    {
        $this->showStamp = $showStamp;

        return $this;
    }

    /**
     * Get showStamp.
     *
     * @return bool
     */
    public function getShowStamp()
    {
        return $this->showStamp;
    }
    

    /**
     * Set fireInspection.
     *
     * @param string $fireInspection
     *
     * @return PdfSettings
     */
    public function setFireInspection($fireInspection)
    {
        $this->fireInspection = $fireInspection;

        return $this;
    }

    /**
     * Get fireInspection.
     *
     * @return string
     */
    public function getFireInspection()
    {
        return $this->fireInspection;
    }

 

    /**
     * Set inspectors.
     *
     * @param array $inspectors
     *
     * @return PdfSettings
     */
    public function setInspectors($inspectors)
    {
        $this->inspectors = $inspectors;

        return $this;
    }

    /**
     * Get inspectors.
     *
     * @return array
     */
    public function getInspectors()
    {
        return $this->inspectors;
    }
}
