<?php

namespace MicroBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use MicroBundle\Enums\OffertStatusEnum;

/**
 * Offert
 *
 * @ORM\Table(name="offert")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\OffertRepository")
 */
class Offert
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
     * @var int
     *
     * @ORM\Column(name="client", type="integer")
     */
    private $client;

    /**
     * @var string
     *
     * @ORM\Column(name="myCompany", type="text", nullable=true)
     */
    private $myCompany;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="addDate", type="datetime")
     */
    private $addDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expireDate", type="datetime")
     */
    private $expireDate;

    /**
     * @var int
     *
     * @ORM\Column(name="totalValue", type="integer")
     */
    private $totalValue;

    /**
     * @var string|null
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * One Offert has many OffPositions. This is the inverse side.
     * @ORM\OneToMany(targetEntity="OffPosition", mappedBy="offert", cascade={"persist"})
     */
    private $offPositions;

    /**
     * One Offert has many OffService. This is the inverse side.
     * @ORM\OneToMany(targetEntity="OffService", mappedBy="offert", cascade={"persist"})
     */
    private $offServices;

    /**
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * Offert constructor.
     */
    public function __construct()
    {
        $this->offPositions = new ArrayCollection();
        $this->status = OffertStatusEnum::BASKET;
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
     * @return Offert
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
     * Set client.
     *
     * @param int $client
     *
     * @return Offert
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client.
     *
     * @return int
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set myCompany.
     *
     * @param string $myCompany
     *
     * @return Offert
     */
    public function setMyCompany($myCompany)
    {
        $this->myCompany = $myCompany;

        return $this;
    }

    /**
     * Get myCompany.
     *
     * @return string
     */
    public function getMyCompany()
    {
        return $this->myCompany;
    }

    /**
     * Set addDate.
     *
     * @param \DateTime $addDate
     *
     * @return Offert
     */
    public function setAddDate($addDate)
    {
        $this->addDate = $addDate;

        return $this;
    }

    /**
     * Get addDate.
     *
     * @return \DateTime
     */
    public function getAddDate()
    {
        return $this->addDate;
    }

    /**
     * Set expireDate.
     *
     * @param \DateTime $expireDate
     *
     * @return Offert
     */
    public function setExpireDate($expireDate)
    {
        $this->expireDate = $expireDate;

        return $this;
    }

    /**
     * Get expireDate.
     *
     * @return \DateTime
     */
    public function getExpireDate()
    {
        return $this->expireDate;
    }

    /**
     * Set totalValue.
     *
     * @param int $totalValue
     *
     * @return Offert
     */
    public function setTotalValue($totalValue)
    {
        $this->totalValue = $totalValue;

        return $this;
    }

    /**
     * Get totalValue.
     *
     * @return int
     */
    public function getTotalValue()
    {
        return $this->totalValue;
    }

    /**
     * Set comment.
     *
     * @param string|null $comment
     *
     * @return Offert
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
     * Set offPositions.
     *
     * @param string|null $offPositions
     *
     * @return Offert
     */
    public function setOffPositions($offPositions = null)
    {
        $this->offPositions = $offPositions;

        return $this;
    }

    /**
     * Get offPositions.
     *
     * @return string|null
     */
    public function getOffPositions()
    {
        return $this->offPositions;
    }

    /**
     * Add offPosition.
     *
     * @param \MicroBundle\Entity\OffPosition $offPosition
     *
     * @return Offert
     */
    public function addOffPosition(\MicroBundle\Entity\OffPosition $offPosition)
    {
        $this->offPositions[] = $offPosition;

        return $this;
    }

    /**
     * Remove offPosition.
     *
     * @param \MicroBundle\Entity\OffPosition $offPosition
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeOffPosition(\MicroBundle\Entity\OffPosition $offPosition)
    {
        return $this->offPositions->removeElement($offPosition);
    }

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return Offert
     */
    public function setStatus($status)

    {
        if (!in_array($status, OffertStatusEnum::getAvailableStatuses())) {
            throw new \InvalidArgumentException("Invalid type");
        }

        $this->status = $status;

        return $this;
    }


    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add offService.
     *
     * @param \MicroBundle\Entity\OffService $offService
     *
     * @return Offert
     */
    public function addOffService(\MicroBundle\Entity\OffService $offService)
    {
        $this->offServices[] = $offService;

        return $this;
    }

    /**
     * Remove offService.
     *
     * @param \MicroBundle\Entity\OffService $offService
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeOffService(\MicroBundle\Entity\OffService $offService)
    {
        return $this->offServices->removeElement($offService);
    }

    /**
     * Get offServices.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOffServices()
    {
        return $this->offServices;
    }
}
