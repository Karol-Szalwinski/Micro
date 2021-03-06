<?php

namespace MicroBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank(message= "Imię nie może być puste")
     * @Assert\Length(
     *      min = 3,
     *      max = 15,
     *      minMessage = "Imię musi zawierać co najmniej {{ limit }} znaki",
     *      maxMessage = "Imię nie może być dłuższe niż {{ limit }} znaków"
     * )
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=15)
     */
    private $name;

    /**
     * @Assert\NotBlank(message= "Nazwisko nie może być puste")
     * @Assert\Length(
     *      min = 3,
     *      max = 25,
     *      minMessage = "Nazwisko musi zawierać co najmniej {{ limit }} znaki",
     *      maxMessage = "Nazwisko nie może być dłuższa niż {{ limit }} znaków"
     * )
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=25)
     */
    private $surname;

    /**
     * @Assert\NotBlank(message= "Musisz wpisać numer uprawnienia")
     * @Assert\Length(
     *      min = 2,
     *      max = 25,
     *      minMessage = "Numer musi zawierać co najmniej {{ limit }} znaki",
     *      maxMessage = "Numer nie może być dłuższa niż {{ limit }} znaków"
     * )
     * @var string
     *
     * @ORM\Column(name="license", type="string", length=25)
     */
    private $license;

    /**
     * One inspector has many stamps. This is the inverse side.
     * @ORM\OneToMany(targetEntity="Stamp", mappedBy="inspector")
     */
    private $stamps;

    /**
     * @var bool
     *
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted;


    /**
     * Inspector constructor.
     */
    public function __construct()
    {
        $this->stamps = new ArrayCollection();
        $this->deleted = false;
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

    /**
     * Set deleted.
     *
     * @param bool $deleted
     *
     * @return Inspector
     */
    public function setDeleted($deleted = true)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted.
     *
     * @return bool
     */
    public function getDeleted()
    {
        return $this->deleted;
    }
}
