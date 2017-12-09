<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductLanguageType;
use AppBundle\Form\ProductPropertyLanguageType;
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
     * @Route("/{langCode}/{page}/{limit}/{orderBy}/{orderDir}", name="product_index", requirements={"langCode" = "[a-z]{2}"})
     * @Method("GET")
     */
    public function indexAction(Request $request, string $langCode, int $page, int $limit, string $orderBy, string $orderDir)
    {
        $filters = $request->query->all();

        return $this->get('response.product')->byCountry($langCode, $page, $orderBy, $orderDir, $filters);
    }

    /**
     * Lists all product entities.
     *
     * @Route("/{langCode}/{page}/{limit}/{orderBy}/{orderDir}", name="product_search", requirements={"langCode" = "[a-z]{2}"})
     * @Method("POST")
     */
    public function searchAction(Request $request, string $langCode, int $page, int $limit, string $orderBy, string $orderDir)
    {
        $data = json_decode($request->getContent(), true);

        return $this->get('response.product')->byCountry($langCode, $data['word'], $page, $orderBy, $orderDir);

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
     * @Rest\View(statusCode=202)
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
        //$prop = new \AppBundle\Entity\ProductPropertyLanguage();
        $data = json_decode($request->getContent(), true);

        //print_r($data);exit;

        $form = $this->createForm(ProductPropertyLanguageType::class);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $pm = $this->get('manager.product');
            $product = $pm->findOneBy(['id' => $productId]);

            $formData = $form->getData();

            $pm->addProperty($formData);
            $pm->save();
            return $this->get('response.product')->fullProductByProduct($product);
        }

        return (new \AppBundle\Helpers\FormException(406, $form))->response();
    }

    /**
     * Update property language to Product
     *
     * @Route("/{productId}/property/language/{languageId}", name="product_update_language_property")
     * @Method("PATCH")
     * @Rest\View(statusCode=202)
     */
    public function patchPropertyLanguageAction(Request $request, int $productId, int $languageId)
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(ProductPropertyLanguageType::class);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $pm = $this->get('manager.product');
            $product = $pm->findOneBy(['id' => $productId]);
            $ppm = $pm->getProductPropertyManager();
            $ppl = $ppm->getLanguageById($languageId);
            $ppl->setName($formData['name']);
            //$pm->addProperty($formData);
            $pm->save();
            return $this->get('response.product')->fullProductByProduct($product);
        }

        return (new \AppBundle\Helpers\FormException(406, $form))->response();
    }

    /**
     * Add property to Product
     *
     * @Route("/{productId}/property/{propertyId}/language", name="product_add_language_property")
     * @Method("POST")
     * @Rest\View(statusCode=201)
     */
    public function postPropertyLanguageAction(Request $request, int $productId, int $propertyId)
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(ProductPropertyLanguageType::class);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $pm = $this->get('manager.product');
            $product = $pm->findOneBy(['id' => $productId]);
            $ppm = $pm->getProductPropertyManager();
            $productProperty = $ppm->getPropertyById($propertyId);

            $ppl = new \AppBundle\Entity\ProductPropertyLanguage();
            $ppl->setName($formData['name']);
            $ppl->setLangCode($formData['langCode']);

            $productProperty->addLanguage($ppl);

            //$pm->addProperty($formData);
            $pm->save();
            return $this->get('response.product')->fullProductByProduct($product);
        }

        return (new \AppBundle\Helpers\FormException(406, $form))->response();
    }

    /**
     * Update property value language
     *
     * @Route("/{productId}/property/{propertyId}/value/{propertyValueId}/language/{langCode}", name="product_update_language_property_value")
     * @Method("PATCH")
     * @Rest\View(statusCode=202)
     */
    public function patchPropertyValueLanguageAction(Request $request, int $productId, int $propertyId, int $propertyValueId, string $langCode)
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(ProductPropertyLanguageType::class);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $pm = $this->get('manager.product');
            $product = $pm->findOneBy(['id' => $productId]);
            $ppm = $pm->getProductPropertyManager();
            $ppm->updatePropertyValueLanguage($propertyValueId, $formData);
            $pm->save();
            return $this->get('response.product')->fullProductByProduct($product);
        }
        return (new \AppBundle\Helpers\FormException(406, $form))->response();
    }

    /**
     * Update property value language
     *
     * @Route("/{productId}/property/{propertyId}/value", name="product_new_property_value")
     * @Method("POST")
     * @Rest\View(statusCode=201)
     */
    public function postPropertyValueAction(Request $request, int $productId, int $propertyId)
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(ProductPropertyLanguageType::class);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $pm = $this->get('manager.product');
            $ppm = $pm->getProductPropertyManager();
            $ppm->addNewPropertyValue($propertyId, $formData);
            $ppm->save();
            return $this->get('response.product')->fullProduct($productId);
        }
    }

    /**
     * Delete property value
     *
     * @Route("/{productId}/property/{propertyId}/value/{propertyValueId}", name="product_delete_property_value")
     * @Method("DELETE")
     * @Rest\View(statusCode=202)
     */
    public function deletePropertyValue(Request $request, int $productId, int $propertyValueId)
    {
        $pm = $this->get('manager.product');
        $ppm = $pm->getProductPropertyManager();
        $ppm->deletePropertyValue($propertyValueId);
        return $this->get('response.product')->fullProduct($productId);
    }

    /**
     * Delete property
     *
     * @Route("/{productId}/property/{propertyId}", name="product_delete_property")
     * @Method("DELETE")
     * @Rest\View(statusCode=202)
     */
    public function deletePropertyAction(Request $request, int $productId, int $propertyId)
    {
        $pm = $this->get('manager.product');
        $ppm = $pm->getProductPropertyManager();
        $ppm->deleteProperty($propertyId);
        return $this->get('response.product')->fullProduct($productId);
    }

    /**
     * Add products to category
     *
     * @Route("/category/{categoryId}", name="product_add_category_to_products")
     * @Method("POST")
     * @Rest\View(statusCode=201)
     */
    public function addCategoryToProducts(Request $request, int $categoryId)
    {
        $cm = $this->get('manager.category');
        $pm = $this->get('manager.product');

        $category = $cm->findOneBy(['id' => $categoryId]);

        $productsId = json_decode($request->getContent(), true);
        $addedList = [];
        foreach ($productsId as $productId) {
            $product = $pm->findOneBy(['id' => $productId]);

            if ($pm->addCategory($category)) {
                $pm->save();
                $addedList[] = $productId;
            }
        }
        return $addedList;
        //return $this->get('response.product')->byCountry('pl', $page);
    }

    /**
     * Add products to category
     *
     * @Route("/category/{categoryId}/{productId}", name="product_delete_category_form_product")
     * @Method("DELETE")
     * @Rest\View(statusCode=202)
     */
    public function deleteCategoryFromProduct(Request $request, int $categoryId, int $productId)
    {
        $pm = $this->get('manager.product');
        $cm = $this->get('manager.category');
        $product = $pm->findOneBy(['id' => $productId]);
        $category = $cm->findOneBy(['id' => $categoryId]);
        $pm->removeCategory($category);
        $pm->save();
        return ['OK'];
        //return $this->get('response.product')->byCountry('pl', $page);
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
