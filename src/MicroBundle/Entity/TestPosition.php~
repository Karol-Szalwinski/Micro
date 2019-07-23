<?php

namespace MicroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TestPosition
 *
 * @ORM\Table(name="test_position")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\TestPositionRepository")
 */
class TestPosition
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
     * @var bool
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
     * Many InspectedDevices have One Fire Inspection
     * @ORM\ManyToOne(targetEntity="MicroBundle\Entity\FireInspection", inversedBy="testPositions", cascade={"persist"})
     * @ORM\JoinColumn(name="fire_inspection_id", referencedColumnName="id")
     */
    private $fireInspection;

    /**
     * TestPosition constructor.
     *
     */
    public function __construct()
    {
        $this->test = true;
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
     * @return TestPosition
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
     * Set test.
     *
     * @param bool $test
     *
     * @return TestPosition
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
     * @param string $comment
     *
     * @return TestPosition
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
     * Set fireInspection.
     *
     * @param \MicroBundle\Entity\FireInspection|null $fireInspection
     *
     * @return TestPosition
     */
    public function setFireInspection(\MicroBundle\Entity\FireInspection $fireInspection = null)
    {
        $this->fireInspection = $fireInspection;

        return $this;
    }

    /**
     * Get fireInspection.
     *
     * @return \MicroBundle\Entity\FireInspection|null
     */
    public function getFireInspection()
    {
        return $this->fireInspection;
    }
}
