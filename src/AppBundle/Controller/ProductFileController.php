<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductFileType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Productlanguage controller.
 *
 * @Route("product/{productId}/file/{langCode}")
 */
class ProductFileController extends FOSRestController
{
    /**
     * Add file
     *
     * @Route("/", name="product_file_add")
     * @Method("POST")
     * @Rest\View(statusCode=201)
     */
    public function addFileAction(Request $request, int $productId, string $langCode)
    {
        //$data = json_decode($request->getContent(), true);
        $productFile = new \AppBundle\Entity\ProductFile();
        $form = $this->createForm(ProductFileType::class, $productFile);
        $form->submit($request->request->all());
        if ($form->isSubmitted() && $form->isValid()) {
            $productFile->setFile($request->files->get('file'));
            $productFile->setLangCode($langCode);
            $pm = $this->get('manager.product');
            $pm->findOneBy(['id' => $productId]);
            $pm->addNewFile($productFile);
            $pm->save();

            return $this->get('response.product')->allFilesByLanguage($productId, $langCode);
        }

        return (new \AppBundle\Helpers\FormException(406, $form))->response();
    }

    /**
     * update file
     *
     * @Route("/{fileId}", name="product_file_update")
     * @Method("POST")
     * @Rest\View(statusCode=201)
     */
    public function updateFileAction(Request $request, int $productId, string $langCode, int $fileId)
    {
        $pm = $this->get('manager.product');
        $pfm = $pm->getProductFileManager();
        $productFile = $pfm->findOneBy(['id' => $fileId]);

        $form = $this->createForm(ProductFileType::class, $productFile, ['method' => 'PATCH']);
        $form->submit($request->request->all());
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('file');
            if ($file) {
                $productFile->setFile($file);
            }

            $pm->updateFile($productFile);
            $pm->save();

            return $this->get('response.product')->allFilesByLanguage($productId, $langCode);
        }

        return (new \AppBundle\Helpers\FormException(406, $form))->response();
    }

    /**
     * get list files by language code file
     *
     * @Route("/", name="product_files_list")
     * @Method("GET")
     * @Rest\View(statusCode=200)
     */
    public function getFilesAction(Request $request, int $productId, string $langCode)
    {
        return $this->get('response.product')->allFilesByLanguage($productId, $langCode);
    }

    /**
     * Delete Product Image
     *
     * @Route("/{fileId}", name="product_file_delete")
     * @Method("DELETE")
     * @Rest\View(statusCode=202)
     */
    public function deleteFileAction(Request $request, int $productId, string $langCode, int $fileId)
    {
        $pm = $this->get('manager.product');
        $product = $pm->findOneBy(['id' => $productId]);
        $productFile = $product->getFileById($fileId);
        $pfm = $pm->getProductFileManager();
        $pfm->deleteFile($productFile);
        $product->removeFile($productFile);
        $pm->save();
        $array = $this->get('response.product')->allFilesByLanguage($productId, $langCode);
        $newArray = [];
        foreach ($array as $item) {
            $newArray[] = $item;
        }

        return $newArray;
    }
}
