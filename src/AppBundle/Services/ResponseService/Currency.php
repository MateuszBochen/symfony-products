<?php

namespace AppBundle\Services\ResponseService;

use AppBundle\Repository\CurrencyRepository;

class Currency
{
    private $countryRepository;

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    public function all():array
    {
        return $this->currencyRepository->findAll();
    }
}
