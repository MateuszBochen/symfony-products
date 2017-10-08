<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProductProperties
 *
 * @ORM\Table(name="product_properties", uniqueConstraints={@ORM\UniqueConstraint(name="languageKey", columns={"product_id", "langCode", "name"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductPropertiesRepository")
 */
class ProductProperties
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
     * @var array
     *
     * @ORM\Column(name="value", type="array")
     * @Assert\NotBlank()
     */
    private $value;

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
     * @return ProductProperties
     */
    public function setLangCode(string $langCode)
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
     * @return ProductProperties
     */
    public function setName(string $name)
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
     * Set value
     *
     * @param array $value
     *
     * @return ProductProperties
     */
    public function setValue(array $value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return array
     */
    public function getValue()
    {
        return $this->value;
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
