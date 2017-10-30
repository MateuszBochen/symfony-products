<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Country controller.
 *
 * @Route("currency")
 */
class CurrencyController extends FOSRestController
{
	/**
     * Lists all productLanguage entities.
     *
     * @Route("/", name="currency_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->get('response.currency')->all();
    }

    /**
     *
     * Route("/import", name="currency_import")
     * Method("GET")
     */
    /*public function importAction()
    {
        //$cm = $this->get('manager.country');
    	$currencyRepo = $this->get('repository.currency');
        $csv = new \parseCSV('/home/backen/Pulpit/waluty.csv');
        echo "<pre>";
        foreach($csv->data as $currencyCsv) {
        	print_r($currencyCsv);
            $currency = new \AppBundle\Entity\Currency();
            $currency->setCurrency($currencyCsv['Currency']);
            $currency->setCode($currencyCsv['AlphabeticCode'] ? $currencyCsv['AlphabeticCode'] : '');
            $currencyRepo->save($currency);

        }

        return $csv->data;
    }*/
}
