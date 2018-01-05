<?php

namespace AppBundle\Entity;

use AppBundle\Validator\Constraints as AppAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProductStorageGroup
 *
 * @ORM\Table(name="product_storage_group")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductStorageGroupRepository")
 */
class ProductStorageGroup
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
     * @var int
     *
     * @ORM\Column(name="sku", type="string", length=255, nullable=true)
     * @AppAssert\UniqueSKU()
     */
    private $sku;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="productStorageGroup")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $product;

    /**
     * JMS\Exclude()
     * @ORM\OneToMany(targetEntity="ProductStorageGroupProperty", mappedBy="productStorageGroup", cascade={"persist"}, orphanRemoval=true)
     * @AppAssert\UniqueProductStorageGroupCombination()
     */
    private $properties;

    /**
     * @ORM\OneToMany(targetEntity="StorageQuantity", mappedBy="productStorageGroup", cascade={"persist"}, orphanRemoval=true)
     */
    private $storageQuantity;

    public function __construct()
    {
        $this->storageQuantity = new ArrayCollection();
        $this->properties = new ArrayCollection();
    }

    public function addProperty(ProductStorageGroupProperty $productStorageGroupProperty)
    {
        $productStorageGroupProperty->setProductStorageGroup($this);
        $this->properties->add($productStorageGroupProperty);

        return $this;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function setProperties($properties)
    {
        foreach ($properties as $property) {
            $property->setProductStorageGroup($this);
            $this->properties->add($property);
        }

        //$this->properties = $properties;

        return $this;
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

    public function addStorageQuantity(StorageQuantity $storageQuantity)
    {
        $storageQuantity->setProduct($this);
        $this->storageQuantity->add($storageQuantity);

        return $this;
    }

    public function getStorageQuantity()
    {
        return $this->storageQuantity;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("allPropertiesHasValue")
     */
    public function getAllPropertiesHasValue()
    {
        $all = count($this->properties);
        if ($all) {
            $compareSum = 0;
            foreach ($this->properties as $property) {
                if ($property->getProductPropertyValue()) {
                    $compareSum += 1;
                }
            }

            if ($all === $compareSum) {
                return true;
            }
        }

        return false;
    }
}
