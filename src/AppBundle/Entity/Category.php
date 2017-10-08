<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;

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
     * One Category has Many Categories.
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent", cascade={"persist"}, orphanRemoval=true)
     */
    private $children;

    /**
     * Many Categories have One Category.
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;
    
    /**
     * @ORM\OneToMany(targetEntity="CategoryLanguage", mappedBy="category", cascade={"persist"}, orphanRemoval=true)
     */
    private $languages;

    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="Product", inversedBy="categories")
     * @ORM\JoinTable(name="products_categories")
     */
    private $products;

    public function __construct() {
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

    public function getLanguage(string $code):CategoryLanguage
    {
        $criteria = Criteria::create();
        
        $criteria->where(Criteria::expr()->eq('langCode', $code));
        
        $langages = $this->languages->matching($criteria);

        if(isset($langages[0])) {
            return $langages[0];
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
}
