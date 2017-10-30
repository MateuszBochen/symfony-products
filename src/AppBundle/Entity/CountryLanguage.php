<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CategoryLanguage
 *
 * @ORM\Table(name="country_language", uniqueConstraints={@ORM\UniqueConstraint(name="languageKey", columns={"country_id", "langCode"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CountryLanguage")
 */
class CountryLanguage
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
     * ISO 639-1:2002, Codes for the representation of names of languages
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
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="languages")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $country;

    public function getId()
    {
        return $this->getId;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setLangCode($langCode)
    {
        $this->langCode = $langCode;

        return $this;
    }

    public function getLangCode()
    {
        return $this->langCode;
    }

    public function setCountry(Country $country)
    {
        $this->country = $country;

        return $this;
    }

    public function getCountry():Country
    {
        return $this->country;
    }
}
