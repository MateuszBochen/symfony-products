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
 * @Route("storage")
 */
class StorageController extends FOSRestController
{
    /**
     * Add Storage
     *
     * @Route("/", name="storage_add")
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
     * Patch Storage
     *
     * @Route("/{storageId}", name="storage_patch", requirements={"storageId" = "[1-9]"}))
     * @Method("PATCH")
     * @Rest\View(statusCode=202)
     */
    public function patchStorageAction(Request $request, int $storageId)
    {
        $sm = $this->get('manager.storage');
        $storage = $sm->getStorageBy(['id' => $storageId]);
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
     * List Storage
     *
     * @Route("/", name="storage_list")
     * @Method("GET")
     * @Rest\View(statusCode=200)
     */
    public function listStorageAction(Request $request)
    {
        return $this->get('response.storage')->getList();
    }

    /**
     * List Storage
     *
     * @Route("/{storageId}", name="storage_full")
     * @Method("GET")
     * @Rest\View(statusCode=200)
     */
    public function getStorageAction(Request $request, int $storageId)
    {
        return $this->get('response.storage')->getFullStorage($storageId);
    }
}
