<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProductProperty
 *
 * @ORM\Table(name="product_properties")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductPropertyRepository")
 */
class ProductProperty
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
     * @ORM\Column(name="isStorageProperty", type="boolean")
     */
    private $isStorageProperty;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="properties")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $product;

    /**
     *
     * @ORM\OneToMany(targetEntity="ProductPropertyLanguage", mappedBy="property", cascade={"persist"}, orphanRemoval=true)
     */
    private $languages;
    // private $language;

    /**
     * @ORM\OneToMany(targetEntity="ProductPropertyValue", mappedBy="property", cascade={"persist"}, orphanRemoval=true)
     */
    private $values;

    public function __construct()
    {
        $this->languages = new ArrayCollection();
        $this->values = new ArrayCollection();
        // $this->ProductStorageGroup = new ArrayCollection();
    }

    /*public function addProductStorageGroup(ProductStorageGroup $ProductStorageGroup)
    {
    $ProductStorageGroup->setProductProperty($this);
    $this->ProductStorageGroup->add($ProductStorageGroup);

    return $this;
    }

    public function getProductStorageGroup()
    {
    return $this->ProductStorageGroup;
    }*/

    /*public function getLanguageById(int $id)
    {
    $criteria = Criteria::create();
    $criteria->where(Criteria::expr()->eq('id', $id));
    $this->language = $this->languages->matching($criteria);
    if (isset($this->language[0])) {
    $this->language = $this->language[0];
    return $this->language;
    }
    }*/

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function addValue(ProductPropertyValue $productPropertyValue)
    {
        $productPropertyValue->setProperty($this);
        $this->values->add($productPropertyValue);

        return $this;
    }

    public function serValue(ProductPropertyValue $productPropertyValue)
    {
        $this->addValue($productPropertyValue);

        return $this;
    }

    public function removeValue(ProductPropertyValue $ProductPropertyValue)
    {
        $this->values->removeElement($ProductPropertyValue);
    }

    public function getValues()
    {
        return $this->values;
    }

    public function setValues($values)
    {
        foreach ($values as $value) {
            $value->setProperty($this);
            $this->values->add($value);
        }
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

    public function addLanguage(ProductPropertyLanguage $productPropertyLanguage)
    {
        $productPropertyLanguage->setProperty($this);
        $this->languages->add($productPropertyLanguage);

        return $this;
    }

    public function getLanguages()
    {
        return $this->languages;
    }

    public function setMainName(string $mainName)
    {
        $this->mainName = $mainName;

        return $this;
    }

    public function getMainName()
    {
        return $this->mainName = $mainName;
    }

    public function setIsStorageProperty($isStorageProperty)
    {
        $this->isStorageProperty = $isStorageProperty;

        return $this;
    }

    public function getIsStorageProperty()
    {
        return $this->isStorageProperty;
    }
}
