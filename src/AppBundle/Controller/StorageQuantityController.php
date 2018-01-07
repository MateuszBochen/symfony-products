<?php

namespace AppBundle\Controller;

use AppBundle\Form\StorageQuantityType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Category controller.
 *
 * @Route("storage/quantity")
 */
class StorageQuantityController extends FOSRestController
{
    /**
     * Add Product to Storage
     *
     * @Route("/{storageId}/product/{productId}", name="storage_add_product")
     * @Method("POST")
     * @Rest\View(statusCode=201)
     */
    public function addProductToStorageAction(Request $request, int $storageId, int $productId)
    {
        $sm = $this->get('manager.storage');
        $storage = $sm->getStorageBy(['id' => $storageId]);

        if (!$storage) {
            throw new HttpException(404, 'Storage not found');
        }

        $pm = $this->get('manager.product');
        $product = $pm->getProductBy(['id' => $productId]);

        if (!$product) {
            throw new HttpException(404, 'Product not found');
        }

        $sm->addProductToStorage($storage, $product);
        $sm->save();
    }

    /**
     * Delete Product to Storage
     *
     * @Route("/{storageId}/product/{productId}", name="storage_delete_product")
     * @Method("DELETE")
     * @Rest\View(statusCode=202)
     */
    public function deleteProductToStorageAction(Request $request, int $storageId, int $productId)
    {
        $sqm = $this->get('manager.storage.quantity');
        $sqm->removeProductFromStroage($storageId, $productId);
    }

    /**
     * Add Product to Storage
     *
     * @Route("", name="storage_patch_quantity")
     * @Method("PATCH")
     * @Rest\View(statusCode=202)
     */
    public function patchStorageQuantityAction(Request $request)
    {
        // StorageQuantityType
        $data = json_decode($request->getContent(), true);

        $sqm = $this->get('manager.storage.quantity');
        $storageQuantity = $sqm->getNewStorageQuantity();

        $form = $this->createForm(StorageQuantityType::class, $storageQuantity);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $sqm->save();
            return ['OK'];
        }

        return (new \AppBundle\Helpers\FormException(406, $form))->response();
    }
}
