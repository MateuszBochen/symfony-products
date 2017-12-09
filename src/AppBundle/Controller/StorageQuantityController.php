<?php

namespace AppBundle\Controller;

use AppBundle\Form\StorageType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/{storageId}", name="storage_add")
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
}
