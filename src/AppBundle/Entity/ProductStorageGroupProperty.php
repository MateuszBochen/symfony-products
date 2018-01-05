<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductStorageGroup
 *
 * @ORM\Table(name="product_storage_group_property", uniqueConstraints={@ORM\UniqueConstraint(name="storagePropCombination", columns={"product_property_id", "product_property_value_id", "product_storage_group_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductStorageGroupPropertyRepository")
 */
class ProductStorageGroupProperty
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
     * @ORM\ManyToOne(targetEntity="ProductProperty")
     * @ORM\JoinColumn(name="product_property_id", referencedColumnName="id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $productProperty;

    /**
     * @ORM\ManyToOne(targetEntity="ProductPropertyValue")
     * @ORM\JoinColumn(name="product_property_value_id", referencedColumnName="id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $productPropertyValue;

    /**
     * @ORM\ManyToOne(targetEntity="ProductStorageGroup", inversedBy="properties")
     * @ORM\JoinColumn(name="product_storage_group_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $productStorageGroup;

    public function getId()
    {
        return $this->id;
    }

    public function setProductStorageGroup($productStorageGroup)
    {
        $this->productStorageGroup = $productStorageGroup;

        return $this;
    }

    public function getProductStorageGroup()
    {
        return $this->productStorageGroup;
    }

    public function setProductProperty(ProductProperty $productProperty)
    {
        $this->productProperty = $productProperty;

        return $this;
    }

    public function getProductProperty()
    {
        return $this->productProperty;
    }

    public function setProductPropertyValue(ProductPropertyValue $productPropertyValue)
    {
        $this->productPropertyValue = $productPropertyValue;

        return $this;
    }

    public function getProductPropertyValue()
    {
        return $this->productPropertyValue;
    }
}
