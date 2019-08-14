<?php

namespace MicroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255, nullable=true)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="postCode", type="string", length=10, nullable=true)
     */
    private $postCode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="taxNumber", type="string", length=10, nullable=true)
     */
    private $taxNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="phoneNo", type="string", length=255, nullable=true)
     */
    private $phoneNo;

    /**
     * @var string
     *
     * @ORM\Column(name="stamp", type="string", length=255, nullable=true)
     */
    private $stamp;

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

    /**
     * Set stamp.
     *
     * @param string $stamp
     *
     * @return MyCompany
     */
    public function setStamp($stamp)
    {
        $this->stamp = $stamp;

        return $this;
    }

    /**
     * Get stamp.
     *
     * @return string
     */
    public function getStamp()
    {
        return $this->stamp;
    }
}
