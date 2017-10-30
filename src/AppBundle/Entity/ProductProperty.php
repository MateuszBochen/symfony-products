<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * ProductProperty
 *
 * @ORM\Table(name="product_properties", uniqueConstraints={@ORM\UniqueConstraint(name="languageKey", columns={"product_id", "langCode", "name"})})
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
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="languages")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $product;

    /**
     * @ORM\OneToMany(targetEntity="ProductPropertyValue", mappedBy="property", cascade={"persist"}, orphanRemoval=true)
     */
    private $values;

    public function __construct()
    {
        $this->values = new ArrayCollection();
    }


    public function addValue(ProductPropertyValue $ProductPropertyValue)
    {   
        $ProductPropertyValue->setProperty($this);
        $this->values->add($ProductPropertyValue);

        return $this;
    }
    
    public function removeValues(ProductPropertyValue $ProductPropertyValue)
    {
        $this->values->removeElement($ProductPropertyValue);
    }

    public function getValues()
    {
        return $this->values;
    }

    public function setValues($values) {
        foreach ($values as $value) {
            $value->setProperty($this);
            $this->values->add($value);            
        }
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
    public function setName(string $name)
    {
        $name = ucfirst(strtolower($name));
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
