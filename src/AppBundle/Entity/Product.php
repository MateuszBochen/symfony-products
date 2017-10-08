<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
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
     * @ORM\Column(name="sku", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $sku;

    /**
     * @var string
     *
     * @ORM\Column(name="width", type="decimal", precision=12, scale=2)
     */
    private $width;

    /**
     * @var string
     *
     * @ORM\Column(name="height", type="decimal", precision=12, scale=2)
     */
    private $height;

    /**
     * @var string
     *
     * @ORM\Column(name="length", type="decimal", precision=12, scale=2)
     */
    private $length;

    /**
     * @var string
     *
     * @ORM\Column(name="weight", type="decimal", precision=12, scale=2)
     */
    private $weight;

    /**
     * @var string
     *
     * @ORM\Column(name="dimensionUnit", type="string", length=10)
     */
    private $dimensionUnit;

    /**
     * @var string
     *
     * @ORM\Column(name="weightUnit", type="string", length=10)
     */
    private $weightUnit;

    /**
     * @var string
     *
     * @ORM\Column(name="vendor", type="string", length=255)
     */
    private $vendor = '';

    /**
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=255)
     */
    private $brand = '';

    /**
     * @ORM\OneToMany(targetEntity="ProductLanguage", mappedBy="product", cascade={"persist"}, orphanRemoval=true)
     */
    private $languages;

    /**
     * @ORM\OneToMany(targetEntity="ProductProperties", mappedBy="product", cascade={"persist"}, orphanRemoval=true)
     */
    private $properties;

    /**
     * Many Groups have Many Users.
     * @ORM\ManyToMany(targetEntity="Category", mappedBy="products")
     */
    private $categories;


    public function __construct()
    {
        $this->languages = new ArrayCollection();
        $this->properties = new ArrayCollection();
        $this->categories = new ArrayCollection();
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
     * Set categories
     *
     * @param string $categories
     *
     * @return Product
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get categories
     *
     * @return string
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set sku
     *
     * @param string $sku
     *
     * @return ProductVariant
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Get sku
     *
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set width
     *
     * @param string $width
     *
     * @return ProductVariant
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param string $height
     *
     * @return ProductVariant
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set length
     *
     * @param string $length
     *
     * @return ProductVariant
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get length
     *
     * @return string
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set weight
     *
     * @param string $weight
     *
     * @return ProductVariant
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set dimensionUnit
     *
     * @param string $dimensionUnit
     *
     * @return ProductVariant
     */
    public function setDimensionUnit($dimensionUnit)
    {
        $this->dimensionUnit = $dimensionUnit;

        return $this;
    }

    /**
     * Get dimensionUnit
     *
     * @return string
     */
    public function getDimensionUnit()
    {
        return $this->dimensionUnit;
    }

    /**
     * Set weightUnit
     *
     * @param string $weightUnit
     *
     * @return ProductVariant
     */
    public function setWeightUnit($weightUnit)
    {
        $this->weightUnit = $weightUnit;

        return $this;
    }

    /**
     * Get weightUnit
     *
     * @return string
     */
    public function getWeightUnit()
    {
        return $this->weightUnit;
    }

    /**
     * Add language
     *
     * @param ProductLanguage $productLanguage
     * @return Product
     */
    public function addLanguage(ProductLanguage $productLanguage)
    {   
        $productLanguage->setProduct($this);
        $this->languages->add($productLanguage);

        return $this;
    }

    /**
     * Remove language
     *
     * @param ProductLanguage $productLanguage
     */
    public function removeLanguage(ProductLanguage $productLanguage)
    {
        $this->languages->removeElement($productLanguage);
    }

    public function getLanguages()
    {
        return $this->languages;
    }

    public function getLanguage(string $code):ProductLanguage
    {
        $criteria = Criteria::create();
        
        $criteria->where(Criteria::expr()->eq('langCode', $code));
        
        $langages = $this->languages->matching($criteria);

        if(isset($langages[0])) {
            return $langages[0];
        }
    }

    /**
     * Add property
     *
     * @param ProductProperties $productProperty
     * @return Product
     */
    public function addProperty(ProductProperties $productProperty)
    {   
        $productProperty->setProduct($this);
        $this->properties->add($productProperty);

        return $this;
    }

    /**
     * Remove property
     *
     * @param ProductProperties $removeProperty
     */
    public function removeProperty(ProductProperties $productProperty)
    {
        $this->properties->removeElement($productProperty);
    }

    public function getProperties():array
    {
        return $this->properties;
    }

    public function getProperty(string $code):array
    {
        $criteria = Criteria::create();

        $criteria->where(Criteria::expr()->eq('langCode', $code));

        return $this->properties->matching($criteria);
    }

    /**
     * Add category
     *
     * @param Category $category
     * @return Product
     */
    public function addCategory(Category $category)
    {
        $category->addProduct($this);
        $this->categories->add($category);

        return $this;
    }

    /**
     * Remove category
     *
     * @param Category $category
     */
    public function removeCategory(Category $category)
    {
        $category->removeProduct($this);
        $this->categories->removeElement($category);
    }
}
