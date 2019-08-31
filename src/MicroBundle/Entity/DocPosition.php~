<?php

namespace MicroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MicroBundle\Entity\Document;

/**
 * DocPosition
 *
 * @ORM\Table(name="doc_position")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\DocPositionRepository")
 */
class DocPosition
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
     * Many DocPosition have One Document
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="docPositions", cascade={"persist"})
     * @ORM\JoinColumn(name="document_id", referencedColumnName="id")
     */
    private $document;

    /**
     * DocPosition constructor.
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
     * @param string|null $name
     *
     * @return DocPosition
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
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
     * @return DocPosition
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
     * @return DocPosition
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
     * Set document.
     *
     * @param Document|null $document
     *
     * @return DocPosition
     */
    public function setDocument(Document $document = null)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document.
     *
     * @return Document|null
     */
    public function getDocument()
    {
        return $this->document;
    }
}
