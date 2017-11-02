<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * ProductImage
 *
 * @ORM\Table(name="product_image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductImageRepository")
 */
class ProductImage
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
     * @ORM\Column(name="main", type="integer")
     */
    private $main;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position = 0;

    /**
     * @JMS\Exclude()
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="images")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $product;

    /**
     * @JMS\Exclude()
     * @ORM\OneToMany(targetEntity="ProductImageLanguage", mappedBy="image", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $languages;

    private $allLanguages;

    /**
     * @ORM\OneToMany(targetEntity="ProductImageSize", mappedBy="image", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $sizes;

    /**
     * @JMS\Exclude()
     */
    private $file;

    public function __construct()
    {
        $this->languages = new ArrayCollection();
        $this->sizes = new ArrayCollection();
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
     * @return ProductImage
     */
    public function setMain($main)
    {
        $this->main = $main;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return ProductImage
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * Add language
     *
     * @param ProductLanguage $productLanguage
     * @return Product
     */
    public function addLanguage(ProductImageLanguage $language)
    {
        $language->setImage($this);
        $this->languages->add($language);

        return $this;
    }

    /**
     * Remove language
     *
     * @param ProductLanguage $productLanguage
     */
    public function removeLanguage(ProductImageLanguage $language)
    {
        $this->languages->removeElement($language);
    }

    public function getAlllanguages()
    {
        $this->allLanguages = $this->languages;
        return $this->allLanguages;
    }

    /**
     * Add size
     *
     * @param ProductLanguage $productLanguage
     * @return Product
     */
    public function addSize(ProductImageSize $size)
    {
        $size->setImage($this);
        $this->sizes->add($size);

        return $this;
    }

    public function setLanguages($languages)
    {
        $this->languages = $languages;
    }

    public function getLanguages()
    {
        return $this->languages;
    }

    public function setSizes($sizes)
    {
        $this->sizes = $sizes;
    }

    public function getSizes()
    {
        return $this->sizes;
    }

    public function removeSize(ProductImageSize $size)
    {
        $this->sizes->removeElement($size);
    }

    public function setFile(UploadedFile $file)
    {
        $this->file = $file;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }
}
