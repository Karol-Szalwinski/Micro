<?php

namespace MicroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DocumentInspector
 *
 * @ORM\Table(name="document_inspector")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\DocumentInspectorRepository")
 */
class DocumentInspector
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
     */
    private $fullname;

    /**
     * @var string
     *
     * @ORM\Column(name="license", type="string", length=255)
     */
    private $license;

    /**
     * Many DocumentInspectors have One FireInspection.
     * @ORM\ManyToOne(targetEntity="FireInspection", inversedBy="documentInspectors", cascade={"persist"})
     * @ORM\JoinColumn(name="fire_inspection_id", referencedColumnName="id")
     */
    private $fireInspection;


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
     * @return DocumentInspector
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
     * @return DocumentInspector
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
     * Get fullname.
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->name . ' ' . $this->surname .' ';
    }

    /**
     * Set license.
     *
     * @param string $license
     *
     * @return DocumentInspector
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
     * Set fireInspection.
     *
     * @param \MicroBundle\Entity\FireInspection|null $fireInspection
     *
     * @return DocumentInspector
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
