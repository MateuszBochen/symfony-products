<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * StorageQuantity
 *
 * @ORM\Table(name="storage_quantity", uniqueConstraints={@ORM\UniqueConstraint(name="productStorageQuantity", columns={"product_id", "storage_id", "property_storage_group_id"})}))
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StorageQuantityRepository")
 */
class StorageQuantity
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
     * @ORM\Column(name="quantity", type="decimal", precision=12, scale=2,)
     * @Assert\NotBlank()
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="Storage", inversedBy="storageQuantity")
     * @ORM\JoinColumn(name="storage_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotBlank()
     */
    private $storage;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="storageQuantity")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotBlank()
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="ProductStorageGroup", inversedBy="productStorageGroup")
     * @ORM\JoinColumn(name="property_storage_group_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $productStorageGroup;

    public function setStorage(Storage $storage)
    {
        $this->storage = $storage;

        return $this;
    }

    public function getStorage()
    {
        return $this->storage;
    }

    public function setProductStorageGroup(ProductStorageGroup $productStorageGroup)
    {
        $this->productStorageGroup = $productStorageGroup;

        return $this;
    }

    public function getProductStorageGroup()
    {
        return $this->productStorageGroup;
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

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set propertyId
     *
     * @param integer $propertyId
     *
     * @return StorageQuantity
     */
    public function setPropertyId($propertyId)
    {
        $this->propertyId = $propertyId;

        return $this;
    }

    /**
     * Get propertyId
     *
     * @return int
     */
    public function getPropertyId()
    {
        return $this->propertyId;
    }

    /**
     * Set propertyValueId
     *
     * @param string $propertyValueId
     *
     * @return StorageQuantity
     */
    public function setPropertyValueId($propertyValueId)
    {
        $this->propertyValueId = $propertyValueId;

        return $this;
    }

    /**
     * Get propertyValueId
     *
     * @return string
     */
    public function getPropertyValueId()
    {
        return $this->propertyValueId;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return StorageQuantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
}
