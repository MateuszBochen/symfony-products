<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductStorageGroupPrices
 *
 * @ORM\Table(name="product_storage_group_prices")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductStorageGroupPriceRepository")
 */
class ProductStorageGroupPrice
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
     */
    private $langCode;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=14, scale=4)
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="ProductStorageGroup", inversedBy="prices")
     * @ORM\JoinColumn(name="product_storage_group_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $productStorageGroup;

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
     * Set langCode
     *
     * @param string $langCode
     *
     * @return ProductStorageGroupPrices
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
     * Set price
     *
     * @param string $price
     *
     * @return ProductStorageGroupPrices
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
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
}
