<?php

namespace MicroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Stamp
 *
 * @ORM\Table(name="stamp")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\StampRepository")
 */
class Stamp
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
     *      min = 1,
     *      max = 20,
     *      minMessage = "Nazwa musi zawierać co najmniej {{ limit }} znak",
     *      maxMessage = "Nazwa nie może być dłuższa niż {{ limit }} znaków"
     * )
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=20)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", nullable=true)
     */
    private $image;

    /**
     * @var bool
     *
     * @ORM\Column(name="main", type="boolean")
     */
    private $main;

    /**
     * Many stamps have one inspector. This is the owning side.
     * @ORM\ManyToOne(targetEntity="Inspector", inversedBy="stamps")
     * @ORM\JoinColumn(name="inspector_id", referencedColumnName="id")
     */
    private $inspector;

    private $imageLabel;

    /**
     * Stamp constructor.
     */
    public function __construct()
    {
        $this->main = false;
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
     * @return Stamp
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
     * Set image.
     *
     * @param string $image
     *
     * @return Stamp
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Get image.
     *
     * @return string
     */
    public function getImageLabel()
    {
        $directPath = "../uploads/images/" . $this->image;
        $inspector = ($this->inspector != null) ? $this->inspector->getFullname() : "Główna";
        return $directPath . "|" . $inspector ;
    }

    /**
     * Set main.
     *
     * @param bool $main
     *
     * @return Stamp
     */
    public function setMain($main)
    {
        $this->main = $main;

        return $this;
    }

    /**
     * Get main.
     *
     * @return bool
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * Set inspector.
     *
     * @param \MicroBundle\Entity\Inspector|null $inspector
     *
     * @return Stamp
     */
    public function setInspector(\MicroBundle\Entity\Inspector $inspector = null)
    {
        $this->inspector = $inspector;

        return $this;
    }

    /**
     * Get inspector.
     *
     * @return \MicroBundle\Entity\Inspector|null
     */
    public function getInspector()
    {
        return $this->inspector;
    }
}
