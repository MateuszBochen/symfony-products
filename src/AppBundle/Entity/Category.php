<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
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
     * @var string
     *
     * @ORM\Column(name="mainName", type="string", length=255)
     */
    private $mainName;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255)
     */
    private $ip = 0;

    /**
     * One Category has Many Categories.
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent", cascade={"persist"}, orphanRemoval=true)
     */
    private $children;
    private $childrenCount;

    /**
     * @JMS\Exclude()
     * Many Categories have One Category.
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     *
     * @JMS\Exclude()
     * @ORM\OneToMany(targetEntity="CategoryLanguage", mappedBy="category", cascade={"persist"}, orphanRemoval=true)
     */
    private $languages;
    private $language;

    /**
     *
     * @JMS\Exclude()
     * @ORM\ManyToMany(targetEntity="Product", inversedBy="categories")
     * @ORM\JoinTable(name="products_categories")
     */
    private $products;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    public function setParent(Category $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param ProductLanguage $productLanguage
     * @return Product
     */
    public function addChild(Category $category)
    {
        $category->setParent($this);
        $this->children->add($category);

        return $this;
    }

    /**
     * Get children
     *
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Remove child
     *
     * @param ProductLanguage $productLanguage
     */
    public function removeChild(Category $category)
    {
        $this->children->removeElement($category);
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
     * @return Category
     */
    public function setMainName($mainName)
    {
        $this->mainName = $mainName;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getMainName()
    {
        return $this->mainName;
    }

    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Add language
     *
     * @param CategoryLanguage $categoryLanguage
     * @return Category
     */
    public function addLanguage(CategoryLanguage $categoryLanguage)
    {
        $categoryLanguage->setCategory($this);
        $this->languages->add($categoryLanguage);

        return $this;
    }

    /**
     * Remove language
     *
     * @param CategoryLanguage $categoryLanguage
     */
    public function removeLanguage(CategoryLanguage $categoryLanguage)
    {
        $this->languages->removeElement($categoryLanguage);
    }

    public function getLanguages()
    {
        return $this->languages;
    }

    public function getLanguage(string $code)
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('langCode', $code));
        $this->language = $this->languages->matching($criteria);
        if (isset($this->language[0])) {
            $this->language = $this->language[0];
            return $this->language;
        }
    }

    public function addProduct(Product $product)
    {
        $this->products->add($product);
    }

    /**
     * Remove category
     *
     * @param Category $category
     */
    public function removeProduct(Product $product)
    {
        $this->products->removeElement($product);
    }

    public function setChildrenCount($count)
    {
        $this->childrenCount = $count;

        return $this;
    }

    public function getChildrenCount()
    {
        return $this->childrenCount;
    }
}
