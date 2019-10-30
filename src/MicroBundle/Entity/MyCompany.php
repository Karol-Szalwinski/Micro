<?php

namespace MicroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MyCompany
 *
 * @ORM\Table(name="my_company")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\MyCompanyRepository")
 */
class MyCompany
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
     *      min = 3,
     *      max = 100,
     *      minMessage = "Nazwa musi zawierać co najmniej {{ limit }} znaki",
     *      maxMessage = "Nazwa nie może być dłuższa niż {{ limit }} znaków"
     * )
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @Assert\NotBlank(message= "Ulica nie może być pusta")
     * @Assert\Length(
     *      min = 3,
     *      max = 40,
     *      maxMessage = "Ulica nie może być dłuższa niż {{ limit }} znaków",
     *      minMessage = "Ulica musi zawierać co najmniej {{ limit }} znaki",
     * )
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=40, nullable=true)
     */
    private $street;

    /**
     * @Assert\NotBlank(message= "Kod pocztowy nie może być pusty")
     * @Assert\Regex(
     *     pattern     = "/\d{2}-\d{3}/",
     *     htmlPattern = "/\d{2}-\d{3}/",
     *     message = "Wprowadź poprawny kod pocztowy w formacie 00-000"
     * )
     * @var string
     *
     * @ORM\Column(name="postCode", type="string", length=10, nullable=true)
     */
    private $postCode;

    /**
     * @Assert\NotBlank(message= "Miasto nie może być puste")
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Miasto nie może być dłuższy niż {{ limit }} znaków",
     * )
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=100, nullable=true)
     */
    private $city;

    /**
     * @Assert\NotBlank(message= "Podaj NIP")
     * @Assert\Regex(
     *     pattern     = "/^((\d{3}[- ]\d{3}[- ]\d{2}[- ]\d{2})|(\d{3}[- ]\d{2}[- ]\d{2}[- ]\d{3}))$/",
     *     htmlPattern = "^((\d{3}[- ]\d{3}[- ]\d{2}[- ]\d{2})|(\d{3}[- ]\d{2}[- ]\d{2}[- ]\d{3}))$",
     *     message = "Wprowadź poprawny NIP 000-000-00-00 lub 000-00-00-000"
     * )
     * @var string
     *
     * @ORM\Column(name="taxNumber", type="string", length=15, nullable=true)
     */
    private $taxNumber;

    /**
     * @Assert\NotBlank(message= "Podaj Numer telefonu")
     * @Assert\Regex(
     *     pattern     = "/^(?:\(?\+?48)?(?:[-\.\(\)\s]*(\d)){9}\)?$/",
     *     htmlPattern = "/^(?:\(?\+?48)?(?:[-\.\(\)\s]*(\d)){9}\)?$/",
     *     message = "Wprowadź poprawny polski nr telefonu np. 000-000-000"
     * )
     * @var string|null
     *
     * @ORM\Column(name="phone_no", type="string", length=20, nullable=true)
     */
    private $phoneNo;


    /**
     * MyCompany constructor.
     * @param int $id
     * @param string $name
     * @param string $street
     * @param string $postCode
     * @param string $city
     * @param string $taxNumber
     * @param string $phoneNo
     */
    public function __construct($id, $name, $street, $postCode, $city, $taxNumber, $phoneNo)
    {
        $this->id = $id;
        $this->name = $name;
        $this->street = $street;
        $this->postCode = $postCode;
        $this->city = $city;
        $this->taxNumber = $taxNumber;
        $this->phoneNo = $phoneNo;
    }

    public function __toString()
    {
        $output = "";
        $output .= $this->name . "\n";
        $output .= $this->street . " ";
        $output .= $this->postCode . " " . $this->city . "\n";
        $output .= "tel: " . $this->phoneNo . "\n";
        return $output;
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
     * @return MyCompany
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
     * Set street.
     *
     * @param string $street
     *
     * @return MyCompany
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street.
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set postCode.
     *
     * @param string $postCode
     *
     * @return MyCompany
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;

        return $this;
    }

    /**
     * Get postCode.
     *
     * @return string
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * Set city.
     *
     * @param string $city
     *
     * @return MyCompany
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set taxNumber.
     *
     * @param string $taxNumber
     *
     * @return MyCompany
     */
    public function setTaxNumber($taxNumber)
    {
        $this->taxNumber = $taxNumber;

        return $this;
    }

    /**
     * Get taxNumber.
     *
     * @return string
     */
    public function getTaxNumber()
    {
        return $this->taxNumber;
    }

    /**
     * Set phoneNo.
     *
     * @param string $phoneNo
     *
     * @return MyCompany
     */
    public function setPhoneNo($phoneNo)
    {
        $this->phoneNo = $phoneNo;

        return $this;
    }

    /**
     * Get phoneNo.
     *
     * @return string
     */
    public function getPhoneNo()
    {
        return $this->phoneNo;
    }

}
