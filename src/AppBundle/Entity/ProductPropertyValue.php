<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProductPropertyValue
 *
 * @ORM\Table(name="product_properties_value", uniqueConstraints={@ORM\UniqueConstraint(name="languageKey", columns={"property_id", "value"})})
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
     * @ORM\Column(name="value", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="ProductProperty", inversedBy="values")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $property;

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
     * Set value
     *
     * @param string $value
     *
     * @return ProductPropertyValue
     */
    public function setValue($value)
    {
        $value = ucfirst(strtolower($value));

        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    public function setProperty(ProductProperty $property)
    {
        $this->property = $property;
    }

    public function getProperty():ProductProperty
    {
        return $this->property;
    }
}

