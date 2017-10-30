<?php

namespace AppBundle\Manager;

use AppBundle\Repository\CountryRepository;
use AppBundle\Entity\Country;
use AppBundle\Entity\CountryLanguage;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CountryManager extends BaseManager
{
    public function __construct(CountryRepository $countryRepository, ValidatorInterface $validator)
    {
        $this->repository = $countryRepository;
        $this->validator = $validator;
    }

    public function getNewCountry():Country
    {
        $country = new Country();

        $this->currentEntity = $country;
        return $country;
    }

    public function findOneBy(array $conditions):Country
    {
        $this->currentEntity = $this->repository->findOneBy($conditions);

        return $this->currentEntity;
    }    

    public function removeLanguage(CountryLanguage $language)
    {
        $this->currentEntity->removeLanguage($language);
    }

    public function addLanguage(CountryLanguage $language)
    {
        $errors = $this->validator->validate($language);

        if ($errors->count()) {
            throw new ManagerValidationException($errors);
        }

        $this->currentEntity->addLanguage($language);
    }
}
