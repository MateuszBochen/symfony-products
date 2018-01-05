<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * ProductPropertyValue
 *
 * @ORM\Table(name="product_properties_value")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductPropertyValueRepository")
 */
class ProductPropertyValue
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
     * @ORM\Column(name="main_value", type="string", length=255, nullable=true)
     */
    private $mainValue;

    /**
     * @JMS\Exclude()
     * @ORM\ManyToOne(targetEntity="ProductProperty", inversedBy="values")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $property;

    /**
     *
     * @ORM\OneToMany(targetEntity="ProductPropertyValueLanguage", mappedBy="propertyValue", cascade={"persist"}, orphanRemoval=true)
     */
    private $languages;
    private $language;

    public function __construct()
    {
        $this->languages = new ArrayCollection();
        // $this->ProductStorageGroup = new ArrayCollection();
    }

    /*public function addProductStorageGroup(ProductStorageGroup $ProductStorageGroup)
    {
    $ProductStorageGroup->setProductPropertyValue($this);
    $this->ProductStorageGroup->add($ProductStorageGroup);

    return $this;
    }

    public function getProductStorageGroup()
    {
    return $this->ProductStorageGroup;
    }*/

    public function addLanguage(ProductPropertyValueLanguage $productPropertyValueLanguage)
    {
        $productPropertyValueLanguage->setPropertyValue($this);
        $this->languages->add($productPropertyValueLanguage);

        return $this;
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

    public function setMainValue($mainValue)
    {
        $this->mainValue = $mainValue;

        return $this;
    }

    public function getMainValue()
    {
        return $this->mainValue;
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

    public function setProperty(ProductProperty $property)
    {
        $this->property = $property;
    }

    public function getProperty(): ProductProperty
    {
        return $this->property;
    }
}
