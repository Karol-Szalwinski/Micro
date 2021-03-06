<?php

namespace MicroBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use MicroBundle\Entity\Parameter;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\CategoryRepository")
 */
class Category
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
     * @Assert\NotBlank(message= "Nazwa kategorii nie może być pusta")
     * @Assert\Length(
     *      min = 3,
     *      max = 15,
     *      minMessage = "Nazwa musi zawierać co najmniej {{ limit }} znaki",
     *      maxMessage = "Nazwa nie może być dłuższa niż {{ limit }} znaków"
     * )
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Parameter", mappedBy="category",  cascade={"persist"})
     */
    private $parameters;

    /**
     * One Category has Many Categories.
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    private $children;

    /**
     * Many Categories have One Category.
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255, nullable=true)
     */
    private $icon;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category",  cascade={"persist"})
     */
    private $products;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
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
     * @return Category
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
     * Set parameters.
     *
     * @param string $parameters
     *
     * @return Category
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Get parameters.
     *
     * @return string
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Set icon.
     *
     * @param string $icon
     *
     * @return Category
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon.
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Add child.
     *
     * @param \MicroBundle\Entity\Category $child
     *
     * @return Category
     */
    public function addChild(\MicroBundle\Entity\Category $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child.
     *
     * @param \MicroBundle\Entity\Category $child
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeChild(\MicroBundle\Entity\Category $child)
    {
        return $this->children->removeElement($child);
    }

    /**
     * Get children.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent.
     *
     * @param \MicroBundle\Entity\Category|null $parent
     *
     * @return Category
     */
    public function setParent(Category $parent = null)
    {

        $loopTrouble = false;
        $category = $parent;
        while ($category!= null) {
            if ($category===$this){
                $loopTrouble = true;
                $category = null;
            } else {
                $category = $category->getParent();
            }
        }

        if (!$loopTrouble) {
            $this->parent = $parent;
        }

        return $this;
    }

    /**
     * Get parent.
     *
     * @return \MicroBundle\Entity\Category|null
     */
    public function getParent()
    {
        return $this->parent;
    }


    /**
     * @return string
     */
    public function getFullPath()
    {
        $path = $this->name;
        $category = $this;
        while ($category->getParent()) {
            $category = $category->getParent();
            $path = $category->getName() . " >> " . $path;
        }
        return $path;
    }

    /**
     * Add parameter.
     *
     * @param Parameter $parameter
     *
     * @return Category
     */
    public function addParameter(Parameter $parameter)
    {
        $parameter->setCategory($this);
        $this->parameters[] = $parameter;

        return $this;
    }

    /**
     * Remove parameter.
     *
     * @param Parameter $parameter
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeParameter(Parameter $parameter)
    {
        return $this->parameters->removeElement($parameter);
    }

    /**
     * Add product.
     *
     * @param \MicroBundle\Entity\Product $product
     *
     * @return Category
     */
    public function addProduct(\MicroBundle\Entity\Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product.
     *
     * @param \MicroBundle\Entity\Product $product
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProduct(\MicroBundle\Entity\Product $product)
    {
        return $this->products->removeElement($product);
    }

    /**
     * Get products.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }
}
