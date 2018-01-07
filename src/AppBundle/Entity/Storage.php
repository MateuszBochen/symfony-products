<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Storage
 *
 * @ORM\Table(name="storage")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StorageRepository")
 */
class Storage
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="houseNumber", type="string", length=10)
     */
    private $houseNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="postCode", type="string", length=255)
     */
    private $postCode;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=40)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="lat", type="decimal", precision=18, scale=15, nullable=true)
     */
    private $lat;

    /**
     * @var string
     *
     * @ORM\Column(name="lng", type="decimal", precision=18, scale=15, nullable=true)
     */
    private $lng;

    /**
     * @JMS\Exclude()
     * @ORM\OneToMany(targetEntity="StorageQuantity", mappedBy="storage", cascade={"persist"}, orphanRemoval=true)
     */
    private $storageQuantity;

    /**
     * postLoad
     */
    private $hasEmptyProduct = false;

    /**
     * postLoad
     */
    private $productsQuantity = 0;

    public function __construct()
    {
        $this->storageQuantity = new ArrayCollection();
    }

    public function addStorageQuantity(StorageQuantity $storageQuantity)
    {
        $storageQuantity->setStorage($this);
        $this->storageQuantity->add($storageQuantity);

        return $this;
    }

    public function getStorageQuantity()
    {
        return $this->storageQuantity;
    }

    public function setProductsQuantity($quantity)
    {
        $this->productsQuantity = $quantity;

        return $this;
    }

    public function setHasEmptyProduct($hasEmptyProduct)
    {
        $this->hasEmptyProduct = $hasEmptyProduct;

        return $this;
    }

    public function getHasEmptyProduct()
    {
        return $this->hasEmptyProduct;
    }

    /*/**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("quantity")

    public function getStorageQuantityAsInt()
    {
    $quantity = 0;
    foreach ($this->storageQuantity as $quantityEntity) {
    $quantity += $quantityEntity->getQuantity();
    }
    return $quantity;
    }

     *
     * @JMS\VirtualProperty
     * @JMS\SerializedName("hasEmptyProduct")

    public function hasEmptyProduct()
    {
    $criteria = Criteria::create();
    $criteria->where(Criteria::expr()->eq('quantity', 0));
    $results = $this->storageQuantity->matching($criteria);

    if ($results) {
    return true;
    }

    return false;
    }*/

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
     * @return Storage
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
     * Set houseNumber
     *
     * @param string $houseNumber
     *
     * @return Storage
     */
    public function setHouseNumber($houseNumber)
    {
        $this->houseNumber = $houseNumber;

        return $this;
    }

    /**
     * Get houseNumber
     *
     * @return string
     */
    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Storage
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return Storage
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set postCode
     *
     * @param string $postCode
     *
     * @return Storage
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;

        return $this;
    }

    /**
     * Get postCode
     *
     * @return string
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Storage
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set lat
     *
     * @param string $lat
     *
     * @return Storage
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param string $lng
     *
     * @return Storage
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return string
     */
    public function getLng()
    {
        return $this->lng;
    }
}
