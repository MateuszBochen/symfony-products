<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;

/**
 * CategoryLanguage
 *
 * @ORM\Table(name="country")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CountryRepository")
 */
class Country
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
     * @ORM\Column(name="alpha2", type="string", length=4)
     * @Assert\NotBlank()
     * ISO 639-1:2002, Codes for the representation of names of languages
     */
    private $alpha2;

    /**
     * @JMS\Exclude()
     * @ORM\OneToMany(targetEntity="CountryLanguage", mappedBy="country", cascade={"persist"}, orphanRemoval=true)
     */
    private $languages;

    private $language;

    public function __construct()
    {
        $this->languages = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function setAlpha2($alpha2)
    {
        $this->alpha2 = $alpha2;

        return $this;
    }

    public function getAlpha2()
    {
        return $this->alpha2;
    }

    /**
     * Add language
     *
     * @param ProductLanguage $productLanguage
     * @return Product
     */
    public function addLanguage(CountryLanguage $language)
    {   
        $language->setCountry($this);
        $this->languages->add($language);

        return $this;
    }

    /**
     * Remove language
     *
     * @param ProductLanguage $productLanguage
     */
    public function removeLanguage(CountryLanguage $language)
    {
        $this->languages->removeElement($language);
    }

    public function getLanguages()
    {
        return $this->languages;
    }

    public function getLanguage(string $code):CountryLanguage
    {
        $criteria = Criteria::create();
        
        $criteria->where(Criteria::expr()->eq('langCode', $code));
        
        $this->language = $this->languages->matching($criteria);

        if(isset($this->language[0])) {
            $this->language = $this->language[0];
            return $this->language;
        }
    }
}
