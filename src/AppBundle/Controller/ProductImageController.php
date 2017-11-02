<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductImageLanguageType;
use AppBundle\Form\ProductImageType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Productlanguage controller.
 *
 * @Route("product/{productId}/image")
 */
class ProductImageController extends FOSRestController
{
    /**
     * Lists all images
     *
     * @Route("/", name="product_image_index")
     * @Method("GET")
     */
    public function indexAction(int $productId)
    {
        return $this->get('response.product')->allImages($productId);
    }

    /**
     * Add image
     *
     * @Route("/", name="product_image_add")
     * @Method("POST")
     * @Rest\View(statusCode=201)
     */
    public function addImageAction(Request $request, int $productId)
    {
        //$data = json_decode($request->getContent(), true);
        $productImage = new \AppBundle\Entity\ProductImage();
        $form = $this->createForm(ProductImageType::class, $productImage);
        //$form->handleRequest($request);
        $form->submit($request->request->all());
        if ($form->isSubmitted() && $form->isValid()) {
            $productImage->setFile($request->files->get('file'));
            $pm = $this->get('manager.product');
            $pm->findOneBy(['id' => $productId]);
            $pm->addNewImage($productImage);
            $pm->save();
            return $this->get('response.product')->allImages($productId);
        }

        return (new \AppBundle\Helpers\FormException(406, $form))->response();
    }

    /**
     * Update image
     *
     * @Route("/{imageId}", name="product_image_update")
     * @Method("POST")
     * @Rest\View(statusCode=202)
     */
    public function updateImageAction(Request $request, int $productId, int $imageId)
    {
        $pm = $this->get('manager.product');
        $pm->findOneBy(['id' => $productId]);
        $pim = $pm->getProductImageManager();
        $productImage = $pim->findOneBy(['id' => $imageId]);
        $form = $this->createForm(ProductImageType::class, $productImage, ['method' => 'PATCH']);

        $form->submit($request->request->all());
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('file');
            if ($file) {
                $productImage->setFile($file);
            }
            $pm->updateImage($productImage);
            $pm->save();

            return $this->get('response.product')->allImages($productId);
        }

        return (new \AppBundle\Helpers\FormException(406, $form))->response();
    }

    /**
     * Update image
     *
     * @Route("/reorder/{from}/{to}", name="product_image_reorder")
     * @Method("GET")
     * @Rest\View(statusCode=202)
     */
    public function changeImagesOrderAction(Request $request, int $productId, int $from, int $to)
    {
        $pm = $this->get('manager.product');
        $pm->findOneBy(['id' => $productId]);
        $pim = $pm->getProductImageManager();
        $pim->reorderImages($productId, $from, $to);
        $pm->save();
    }

    /**
     * Add language to Product Image
     *
     * @Route("/{imageId}/language/{langCode}", name="product_image_add_language")
     * @Method("PUT")
     * @Rest\View(statusCode=201)
     */
    public function addLanguageAction(Request $request, int $productId, int $imageId, string $langCode)
    {
        $data = json_decode($request->getContent(), true);
        $pm = $this->get('manager.product');

        $pm->findOneBy(['id' => $productId]);
        $pim = $pm->getProductImageManager();
        $productImage = $pim->findOneBy(['id' => $imageId]);
        // $product = $pm->findOneBy(['id' => $productId]);
        $languages = $productImage->getLanguages();
        $language = new \AppBundle\Entity\ProductImageLanguage();
        $language->setLangCode($langCode);
        $langIsFound = false;
        foreach ($languages as $lang) {
            if ($lang->getLangCode() == $langCode) {
                $language = $lang;
                $langIsFound = true;
            }
        }

        $form = $this->createForm(ProductImageLanguageType::class, $language);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$langIsFound) {
                $productImage->addLanguage($language);
            }
            $pm->save();
            return $this->get('response.product')->allImages($productId);
        }

        return (new \AppBundle\Helpers\FormException(406, $form))->response();
    }

    /**
     * Delete Product Image
     *
     * @Route("/{imageId}", name="product_image_delete")
     * @Method("DELETE")
     * @Rest\View(statusCode=202)
     */
    public function deleteImageAction(Request $request, int $productId, int $imageId)
    {
        $pm = $this->get('manager.product');
        $product = $pm->findOneBy(['id' => $productId]);
        $productImage = $product->getImageById($imageId);
        $pim = $pm->getProductImageManager();
        $pim->deleteImage($productImage);
        $product->removeImage($productImage);
        $pm->save();
        return $this->get('response.product')->allImages($productId);
    }
}
