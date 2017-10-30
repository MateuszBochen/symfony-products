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
 * @Route("country")
 */
class CountryController extends FOSRestController
{
    /**
     * Lists all productLanguage entities.
     *
     * @Route("/{langCode}", name="country_index")
     * @Method("GET")
     */
    public function indexAction(string $langCode)
    {
        return $this->get('response.country')->byCountry($langCode);
    }


    /**
     *
     * Route("/import", name="country_import")
     * Method("GET")
     */
    /*public function importAction()
    {
        $cm = $this->get('manager.country');

        $csv = new \parseCSV('/home/backen/Pulpit/kraje.csv');
        foreach($csv->data as $countryCsv) {
            $country = $cm->getNewCategory();
            $country->setAlpha2(strtolower($countryCsv['alpha-2']));

            $countryLang = new \AppBundle\Entity\CountryLanguage();
            $countryLang->setLangCode('en');
            $countryLang->setName($countryCsv['name']);

            $cm->addLanguage($countryLang);

            $cm->save();

        }

        return $csv->data;
    }*/
}
