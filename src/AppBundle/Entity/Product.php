<?php

namespace AppBundle\Entity;

use AppBundle\Validator\Constraints as AppAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(name="sku", type="string", length=100, unique=true)
     * @Assert\NotBlank()
     * @AppAssert\UniqueSKU()
     */
    private $sku;

    /**
     * CODE EAN / EAN-13 = ISBN
     * @var string
     *
     * @ORM\Column(name="ean", type="string", length=13, unique=true, nullable=true)
     */
    private $ean;

    /**
     * Global Trade Item Number
     * @var string
     *
     * @ORM\Column(name="gtin", type="string", length=14, unique=true, nullable=true)
     */
    private $gtin;

    /**
     * @var string
     *
     * @ORM\Column(name="width", type="decimal", precision=12, scale=2, nullable=true)
     */
    private $width;

    /**
     * @var string
     *
     * @ORM\Column(name="height", type="decimal", precision=12, scale=2, nullable=true)
     */
    private $height;

    /**
     * @var string
     *
     * @ORM\Column(name="length", type="decimal", precision=12, scale=2, nullable=true)
     */
    private $length;

    /**
     * @var string
     *
     * @ORM\Column(name="diameter", type="decimal", precision=12, scale=2, nullable=true)
     */
    private $diameter;

    /**
     * @var string
     *
     * @ORM\Column(name="weight", type="decimal", precision=12, scale=2, nullable=true)
     */
    private $weight;

    /**
     * @var string
     *
     * @ORM\Column(name="dimensionUnit", type="string", length=10, nullable=true)
     */
    private $dimensionUnit;

    /**
     * @var string
     *
     * @ORM\Column(name="weightUnit", type="string", length=10, nullable=true)
     */
    private $weightUnit;

    /**
     * @var string
     *
     * @ORM\Column(name="vendor", type="string", length=255, nullable=true)
     */
    private $vendor = '';

    /**
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=255, nullable=true)
     */
    private $brand = '';

    /**
     * @var string
     * ISO 639-1:2002, Codes for the representation of names of languages
     *
     * @ORM\Column(name="country_of_production", type="string", length=3, nullable=true)
     */
    private $countryOfProduction = '';

    /**
     * @JMS\Exclude()
     * @ORM\OneToMany(targetEntity="ProductLanguage", mappedBy="product", cascade={"persist"}, orphanRemoval=true)
     */
    private $languages;

    private $alllanguages;
    private $language;

    /**
     * @JMS\Exclude()
     * @ORM\OneToMany(targetEntity="ProductImage", mappedBy="product", cascade={"persist"}, orphanRemoval=true)
     */
    private $images;

    /**
     * @JMS\Exclude()
     * @ORM\OneToMany(targetEntity="ProductProperty", mappedBy="product", cascade={"persist"}, orphanRemoval=true)
     */
    private $properties;
    private $property;
    private $allProperties;

    /**
     * @JMS\Exclude()
     * @ORM\ManyToMany(targetEntity="Category", mappedBy="products")
     */
    private $categories;

    public function __construct()
    {
        $this->languages = new ArrayCollection();
        $this->images = new ArrayCollection();
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
     * Set EAN
     *
     * @param string $ean
     *
     * @return ProductVariant
     */
    public function setEan($ean)
    {
        $this->ean = $ean;

        return $this;
    }

    /**
     * Get EAN
     *
     * @return string
     */
    public function getEan()
    {
        return $this->ean;
    }

    /**
     * Set GTIN
     *
     * @param string $gtin
     *
     * @return ProductVariant
     */
    public function setGtin($gtin)
    {
        $this->gtin = $gtin;

        return $this;
    }

    /**
     * Get EAN
     *
     * @return string
     */
    public function getGtin()
    {
        return $this->gtin;
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
     * @return Product
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
     * Set diameter
     *
     * @param string $diameter
     *
     * @return Product
     */
    public function setDiameter($diameter)
    {
        $this->diameter = $diameter;

        return $this;
    }

    /**
     * Get diameter
     *
     * @return string
     */
    public function getDiameter()
    {
        return $this->diameter;
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

    public function setVendor($vendor)
    {
        $this->vendor = $vendor;

        return $this;
    }

    public function getVendor()
    {
        return $this->vendor;
    }

    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function setCountryOfProduction($countryOfProduction)
    {
        $this->countryOfProduction = $countryOfProduction;

        return $this;
    }

    public function getCountryOfProduction()
    {
        return $this->countryOfProduction;
    }

    /**
     * Add image
     *
     * @param ProductImage $image
     * @return Product
     */
    public function addImage(ProductImage $image)
    {
        $image->setProduct($this);
        $this->images->add($image);

        return $this;
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

    public function getAlllanguages()
    {
        $this->alllanguages = $this->languages;
        return $this->alllanguages;
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

    public function getPropertyById(int $id)
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('id', $id));
        $this->property = $this->properties->matching($criteria);
        if (isset($this->property[0])) {
            $this->property = $this->property[0];
            return $this->property;
        }
    }

    public function getProperty(string $code): array
    {
        $criteria = Criteria::create();

        $criteria->where(Criteria::expr()->eq('langCode', $code));

        return $this->properties->matching($criteria);
    }

    /**
     * Add property
     *
     * @param ProductProperty $productProperty
     * @return Product
     */
    public function addProperty(ProductProperty $productProperty)
    {
        $productProperty->setProduct($this);
        $this->properties->add($productProperty);

        return $this;
    }

    /**
     * Remove property
     *
     * @param ProductProperty $removeProperty
     */
    public function removeProperty(ProductProperty $productProperty)
    {
        $this->properties->removeElement($productProperty);
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function getAllProperties()
    {
        $this->allProperties = $this->properties;
        return $this->allProperties = $this->allProperties;
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

    public function setCategories($categories)
    {
        //$this->categories = $categories;

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
