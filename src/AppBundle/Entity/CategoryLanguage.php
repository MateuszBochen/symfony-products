<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CategoryLanguage
 *
 * @ORM\Table(name="category_language", uniqueConstraints={@ORM\UniqueConstraint(name="languageKey", columns={"category_id", "langCode"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductLanguageRepository")
 */
class CategoryLanguage
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
     * @ORM\Column(name="langCode", type="string", length=4)
     * @Assert\NotBlank()
     * ISO 639-1:2002, Codes for the representation of names of languages
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
     */
    private $description = '';

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
     * @JMS\Exclude()
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="languages")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $category;

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

    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }
}
