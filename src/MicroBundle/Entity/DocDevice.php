<?php

namespace MicroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DocDevice
 *
 * @ORM\Table(name="doc_device")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\DocDeviceRepository")
 */
class DocDevice
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
     * @ORM\Column(name="shortname", type="string", length=20)
     */
    private $shortname;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="test", type="boolean")
     */
    private $test;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @var bool
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;

    /**
     * Many DocDevices have One Document
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="docDevices", cascade={"persist"})
     * @ORM\JoinColumn(name="document_id", referencedColumnName="id")
     */
    private $document;

    /**
     * Many DocDevices have One BuildDevice
     * @ORM\ManyToOne(targetEntity="BuildDevice", inversedBy="docDevices")
     * @ORM\JoinColumn(name="build_device_id", referencedColumnName="id")
     */
    private $buildDevice;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->status = true;
        $this->test = true;
        $this->visible = true;
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
     * Set loopNo.
     *
     * @param int|null $loopNo
     *
     * @return DocDevice
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
     * @return DocDevice
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
     * Set shortname.
     *
     * @param string $shortname
     *
     * @return DocDevice
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
     * Set status.
     *
     * @param bool $status
     *
     * @return DocDevice
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set test.
     *
     * @param bool $test
     *
     * @return DocDevice
     */
    public function setTest($test)
    {
        $this->test = $test;

        return $this;
    }

    /**
     * Get test.
     *
     * @return bool
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * Set comment.
     *
     * @param string|null $comment
     *
     * @return DocDevice
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
     * Set visible.
     *
     * @param bool $visible
     *
     * @return DocDevice
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible.
     *
     * @return bool
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set document.
     *
     * @param \MicroBundle\Entity\Document|null $document
     *
     * @return DocDevice
     */
    public function setDocument(\MicroBundle\Entity\Document $document = null)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document.
     *
     * @return \MicroBundle\Entity\Document|null
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Set buildDevice.
     *
     * @param \MicroBundle\Entity\BuildDevice|null $buildDevice
     *
     * @return DocDevice
     */
    public function setBuildDevice(\MicroBundle\Entity\BuildDevice $buildDevice = null)
    {
        $this->buildDevice = $buildDevice;

        return $this;
    }

    /**
     * Get buildDevice.
     *
     * @return \MicroBundle\Entity\BuildDevice|null
     */
    public function getBuildDevice()
    {
        return $this->buildDevice;
    }
}
