<?php

namespace MicroBundle\Entity;


use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\NotBlank(message= "Login nie może być pusty")
     * @Assert\Length(
     *      min = 3,
     *      max = 100,
     *      minMessage = "Login musi zawierać co najmniej {{ limit }} znaki",
     *      maxMessage = "Login nie może być dłuższa niż {{ limit }} znaków"
     * )
     * @var string
     */
    protected $username;


    /**
     * @Assert\NotBlank(message= "Imię nie może być puste")
     * @Assert\Length(
     *      min = 3,
     *      max = 100,
     *      minMessage = "Imię musi zawierać co najmniej {{ limit }} znaki",
     *      maxMessage = "Imię nie może być dłuższa niż {{ limit }} znaków"
     * )
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @Assert\NotBlank(message= "Nazwisko nie może być puste")
     * @Assert\Length(
     *      min = 3,
     *      max = 100,
     *      minMessage = "Nazwisko musi zawierać co najmniej {{ limit }} znaki",
     *      maxMessage = "Nazwisko nie może być dłuższa niż {{ limit }} znaków"
     * )
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=100, nullable=true)
     */
    private $surname;

    /**
     * @Assert\Email(
     *     message = "Adres email '{{ value }}' nie jest poprawny",
     *     checkMX = true
     * )
     * @var string
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255, nullable=true)
     */
    private $icon;



    public function __construct()
    {
        parent::__construct();
        $this->enabled = 1;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     *
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }


    /**
     * Set icon.
     *
     * @param string|null $icon
     *
     * @return User
     */
    public function setIcon($icon = null)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon.
     *
     * @return string|null
     */
    public function getIcon()
    {
        return $this->icon;
    }


}
