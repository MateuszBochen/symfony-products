<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ProductLanguage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Productlanguage controller.
 *
 * @Route("productlanguage")
 */
class ProductLanguageController extends Controller
{
    /**
     * Lists all productLanguage entities.
     *
     * @Route("/", name="productlanguage_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $productLanguages = $em->getRepository('AppBundle:ProductLanguage')->findAll();

        return $this->render('productlanguage/index.html.twig', array(
            'productLanguages' => $productLanguages,
        ));
    }

    /**
     * Finds and displays a productLanguage entity.
     *
     * @Route("/{id}", name="productlanguage_show")
     * @Method("GET")
     */
    public function showAction(ProductLanguage $productLanguage)
    {

        return $this->render('productlanguage/show.html.twig', array(
            'productLanguage' => $productLanguage,
        ));
    }
}
