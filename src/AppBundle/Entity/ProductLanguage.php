<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProductLanguage
 *
 * @ORM\Table(name="product_language", uniqueConstraints={@ORM\UniqueConstraint(name="languageProduct", columns={"product_id", "langCode"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductLanguageRepository")
 */
class ProductLanguage
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
     * ISO 639-1:2002, Codes for the representation of names of languages
     * 
     * @ORM\Column(name="langCode", type="string", length=4)
     * @Assert\NotBlank()
     */
    private $langCode;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="subName", type="string", length=255)
     */
    private $subName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="fullDescription", type="text")
     */
    private $fullDescription = '';


    /**
     * @var string
     *
     * @ORM\Column(name="metaTitle", type="string", length=255)
     */
    private $metaTitle = '';

    /**
     * @var string
     *
     * @ORM\Column(name="metaDescription", type="string", length=255)
     */
    private $metaDescription = '';


    /**
     * @var string
     *
     * @ORM\Column(name="metaKeywords", type="string", length=255)
     */
    private $metaKeywords = '';

    /**
     * @var string
     * 
     * @ORM\Column(name="tax", type="decimal", precision=12, scale=2)
     */
    private $tax = 0.00;

    /**
     * @var string
     * 
     * @ORM\Column(name="price", type="decimal", precision=12, scale=2)
     */
    private $price = 0.00;

    /**
     * @var string
     * ISO 4217 Currency Codes
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="currency", type="string", length=3)
     */
    private $currency;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="languages")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $product;

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
     * Set langCode
     *
     * @param string $langCode
     *
     * @return ProductLanguage
     */
    public function setLangCode($langCode)
    {
        $this->langCode = $langCode;

        return $this;
    }

    /**
     * Get langCode
     *
     * @return string
     */
    public function getLangCode()
    {
        return $this->langCode;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ProductLanguage
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
     * Set subName
     *
     * @param string $subName
     *
     * @return ProductLanguage
     */
    public function setSubName($subName)
    {
        $this->subName = $subName;

        return $this;
    }

    /**
     * Get subName
     *
     * @return string
     */
    public function getSubName()
    {
        return $this->subName;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return ProductLanguage
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set fullDescription
     *
     * @param string $fullDescription
     *
     * @return ProductLanguage
     */
    public function setFullDescription($fullDescription)
    {
        $this->fullDescription = $fullDescription;

        return $this;
    }

    /**
     * Get fullDescription
     *
     * @return string
     */
    public function getFullDescription()
    {
        return $this->fullDescription;
    }

    /**
     * Set fullDescription
     *
     * @param string $fullDescription
     *
     * @return ProductLanguage
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * Get fullDescription
     *
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * Set fullDescription
     *
     * @param string $fullDescription
     *
     * @return ProductLanguage
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get fullDescription
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set fullDescription
     *
     * @param string $fullDescription
     *
     * @return ProductLanguage
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Get fullDescription
     *
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }

    public function getProduct()
    {
        return $this->product;
    }
}

