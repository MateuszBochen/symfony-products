<?php

namespace AppBundle\Controller;

use AppBundle\Form\StorageType;
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
     * @Route("/{storageId}", name="storage_quantity_add")
     * @Method("POST")
     * @Rest\View(statusCode=201)
     */
    public function addStorageAction(Request $request)
    {
        $sm = $this->get('manager.storage');
        $storage = $sm->getNewStorage();
        $form = $this->createForm(StorageType::class, $storage);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $sm->save();
            return $storage;
        }
        return (new \AppBundle\Helpers\FormException(406, $form))->response();
    }

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
}
