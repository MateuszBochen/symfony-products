<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
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
        return ['ok' => $productId];
    }

    /**
     * Lists all images
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
            return $this->get('response.product')->fullProduct($productId);
        }

        return (new \AppBundle\Helpers\FormException(406, $form))->response();
    }
}
