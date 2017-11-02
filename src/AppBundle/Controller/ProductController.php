<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductLanguageType;
use AppBundle\Form\ProductPropertyType;
use AppBundle\Form\ProductPropertyValueType;
use AppBundle\Form\ProductType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Productlanguage controller.
 *
 * @Route("product")
 */
class ProductController extends FOSRestController
{
    /**
     * Lists all product entities.
     *
     * @Route("/{langCode}/{page}/{limit}", name="product_index", requirements={"langCode" = "[a-z]{2}"})
     * @Method("GET")
     */
    public function indexAction(string $langCode, int $page, int $limit)
    {
        return $this->get('response.product')->byCountry($langCode);
    }

    /**
     * Lists all product entities.
     *
     * @Route("/{productId}", name="product_full")
     * @Method("GET")
     */
    public function fullPoductAction(int $productId)
    {
        return $this->get('response.product')->fullProduct($productId);
    }

    /**
     * Create new Product
     *
     * @Route("", name="product_create")
     * @Method("POST")
     * @Rest\View(statusCode=201)
     */
    public function createAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $pm = $this->get('manager.product');

        $product = $pm->getNewProduct();
        $form = $this->createForm(ProductType::class, $product);

        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $pm->save();
            return $this->get('response.product')->fullProductByProduct($product);
        }

        $res = new \AppBundle\Helpers\FormException(406, $form);
        return $res->response();
    }

    /**
     * Update Product
     *
     * @Route("/{productId}", name="product_update")
     * @Method("PUT")
     * @Rest\View(statusCode=202)
     */
    public function updateAction(Request $request, $productId)
    {
        $data = json_decode($request->getContent(), true);
        $pm = $this->get('manager.product');

        $product = $pm->findOneBy(['id' => $productId]);
        $form = $this->createForm(ProductType::class, $product);

        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $pm->save();
            return $this->get('response.product')->fullProductByProduct($product);
        }

        $res = new \AppBundle\Helpers\FormException(406, $form);
        return $res->response();
    }

    /**
     * Add language to Product
     *
     * @Route("/language/{productId}/{langCode}", name="product_add_language")
     * @Method("PUT")
     * @Rest\View(statusCode=201)
     */
    public function addLanguageAction(Request $request, int $productId, string $langCode)
    {
        $data = json_decode($request->getContent(), true);
        $pm = $this->get('manager.product');
        $product = $pm->findOneBy(['id' => $productId]);
        $languages = $product->getLanguages();
        $language = new \AppBundle\Entity\ProductLanguage();
        $language->setLangCode($langCode);
        $langIsFound = false;
        foreach ($languages as $lang) {
            if ($lang->getLangCode() == $langCode) {
                $language = $lang;
                $langIsFound = true;
            }
        }

        $form = $this->createForm(ProductLanguageType::class, $language);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$langIsFound) {
                $pm->addLanguage($language);
            }
            $pm->save();
            return $this->get('response.product')->fullProductByProduct($product);
        }

        $res = new \AppBundle\Helpers\FormException(406, $form);
        return $res->response();
    }

    /**
     * Add property to Product
     *
     * @Route("/property/{productId}", name="product_add_property")
     * @Method("PUT")
     * @Rest\View(statusCode=201)
     */
    public function addPropertyAction(Request $request, int $productId)
    {
        $prop = new \AppBundle\Entity\ProductProperty();
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(ProductPropertyType::class, $prop);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $pm = $this->get('manager.product');
            $product = $pm->findOneBy(['id' => $productId]);
            $pm->addProperty($prop);
            $pm->save();
            return $this->get('response.product')->fullProductByProduct($product);
        }

        return (new \AppBundle\Helpers\FormException(406, $form))->response();
    }

    /**
     * Add property to Product
     *
     * @Route("/{productId}/property/value/{propertyValueId}", name="product_delete_property_value")
     * @Method("DELETE")
     * @Rest\View(statusCode=202)
     */
    public function deletePropertyValue(Request $request, int $productId, int $propertyValueId)
    {
        $pm = $this->get('manager.product');
        $pm->removePropertyValue($propertyValueId);
        $pm = $this->get('manager.product');
        return $this->get('response.product')->fullProduct($productId);
    }

    /**
     * Add value to property
     *
     * @Route("/{productId}/property/{propertyId}/value", name="product_add_property_value")
     * @Method("POST")
     * @Rest\View(statusCode=201)
     */
    public function addValueToPropertyAction(Request $request, int $productId, int $propertyId)
    {
        $propValue = new \AppBundle\Entity\ProductPropertyValue();
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(ProductPropertyValueType::class, $propValue);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $pm = $this->get('manager.product');
            $pm->addPropertyValue($propValue, $propertyId);
            return $this->get('response.product')->fullProduct($productId);
        }
        return (new \AppBundle\Helpers\FormException(406, $form))->response();
    }

    /**
     * Add value to property
     *
     * @Route("/{productId}/property/{propertyId}", name="product_update_property")
     * @Method("PATCH")
     * @Rest\View(statusCode=202)
     */
    public function updatePropertyAction(Request $request, int $productId, int $propertyId)
    {
        $data = json_decode($request->getContent(), true);
        $pm = $this->get('manager.product');
        $product = $pm->findOneBy(['id' => $productId]);
        $property = $product->getPropertyById($propertyId);

        $form = $this->createForm(ProductPropertyType::class, $property, ['method' => $request->getMethod()]);
        $form->submit($data, false);
        if ($form->isSubmitted() && $form->isValid()) {
            $pm = $this->get('manager.product');
            $pm->save();
            return $this->get('response.product')->fullProductByProduct($product);
        }
        return (new \AppBundle\Helpers\FormException(406, $form))->response();
    }

    /**
     * Add value to property
     *
     * @Route("/{productId}/property/{propertyId}", name="product_delete_property")
     * @Method("DELETE")
     * @Rest\View(statusCode=202)
     */
    public function deletePropertyAction(Request $request, int $productId, int $propertyId)
    {
        $data = json_decode($request->getContent(), true);
        $pm = $this->get('manager.product');
        $product = $pm->findOneBy(['id' => $productId]);
        $property = $product->getPropertyById($propertyId);
        $pm->removeProperty($property);
        $pm->save();
        return $this->get('response.product')->fullProductByProduct($product);
    }

    /**/
}

/*"brand":"",
"countryOfProduction":"pl",
"diameter":"",
"dimensionUnit":"",
"height":"",
"length":"",
"sku":"sdfsd",
"vendor":"",
"weight":"",
"weightUnit":"",
"width":"",*/
