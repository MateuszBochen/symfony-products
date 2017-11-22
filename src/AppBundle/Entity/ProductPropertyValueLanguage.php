<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProductPropertyValueLanguage
 *
 * @ORM\Table(name="product_properties_value_language")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductPropertyValueLanguageRepository")
 */
class ProductPropertyValueLanguage
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
     * @ORM\Column(name="value", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $value;

    /**
     * @JMS\Exclude()
     * @ORM\ManyToOne(targetEntity="ProductPropertyValue", inversedBy="languages")
     * @ORM\JoinColumn(name="property_value_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $propertyValue;

    public function setPropertyValue(ProductPropertyValue $propertyValue)
    {
        $this->propertyValue = $propertyValue;
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
     * Set langCode
     *
     * @param string $langCode
     *
     * @return ProductProperty
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
     * Set name
     *
     * @param string $name
     *
     * @return ProductProperty
     */
    public function setValue(string $value)
    {
        $value = ucfirst(strtolower($value));
        $this->value = $value;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
