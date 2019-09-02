<?php

namespace MicroBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Inspector
 *
 * @ORM\Table(name="inspector")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\InspectorRepository")
 */
class Inspector
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255)
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="license", type="string", length=255)
     */
    private $license;

    /**
     * One inspector has many stamps. This is the inverse side.
     * @ORM\OneToMany(targetEntity="Stamp", mappedBy="inspector")
     */
    private $stamps;

    /**
     * Inspector constructor.
     */
    public function __construct()
    {
        $this->stamps = new ArrayCollection();
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
     * @return Inspector
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
     * Set surname.
     *
     * @param string $surname
     *
     * @return Inspector
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname.
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set license.
     *
     * @param string $license
     *
     * @return Inspector
     */
    public function setLicense($license)
    {
        $this->license = $license;

        return $this;
    }

    /**
     * Get license.
     *
     * @return string
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * Get fullname.
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->name . ' ' . $this->surname .' ';
    }

    /**
     * Add stamp.
     *
     * @param \MicroBundle\Entity\Stamp $stamp
     *
     * @return Inspector
     */
    public function addStamp(\MicroBundle\Entity\Stamp $stamp)
    {
        $this->stamps[] = $stamp;

        return $this;
    }

    /**
     * Remove stamp.
     *
     * @param \MicroBundle\Entity\Stamp $stamp
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeStamp(\MicroBundle\Entity\Stamp $stamp)
    {
        return $this->stamps->removeElement($stamp);
    }

    /**
     * Get stamps.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStamps()
    {
        return $this->stamps;
    }
}
