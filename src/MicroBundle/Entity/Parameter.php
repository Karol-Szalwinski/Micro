<?php

namespace MicroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MicroBundle\Entity\Category;

/**
 * Parameter
 *
 * @ORM\Table(name="parameter")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\ParameterRepository")
 */
class Parameter
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
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="parameters")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
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

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set category.
     *
     * @param Category|null $category
     *
     * @return Parameter
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return Category|null
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * Add category.
     *
     * @return Category|null
     */
    public function addCategory(Category $category)
    {
        return $this->category;
    }
}
