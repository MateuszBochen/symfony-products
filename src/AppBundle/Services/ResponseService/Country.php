<?php

namespace AppBundle\Services\ResponseService;

use AppBundle\Repository\CountryRepository;

class Country
{
    private $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function byCountry(string $countryCode):array
    {
        //echo $countryCode; exit;

        $countries = $this->countryRepository->findAll();
        
        foreach($countries as $country) {
            $country->getLanguage($countryCode);
        }

        return $countries;
    }
}
