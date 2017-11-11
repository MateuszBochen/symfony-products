<?php

namespace AppBundle\Controller;

use AppBundle\Form\ProductCategoryType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Category controller.
 *
 * @Route("category")
 */
class CategoryController extends FOSRestController
{

    /**
     * List categories
     *
     * @Route("/{langCode}/{parentId}", name="category_list_by_country")
     * @Method("GET")
     * @Rest\View(statusCode=200)
     */
    public function getListAction(Request $request, string $langCode, int $parentId)
    {
        return $this->get('response.category')->byCountry($langCode, $parentId);
    }

    /**
     * Add category
     *
     * @Route("/", name="category_add")
     * @Method("POST")
     * @Rest\View(statusCode=201)
     */
    public function addCategoryAction(Request $request)
    {
        $form = $this->createForm(ProductCategoryType::class);

        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $cm = $this->get('manager.category');

            $cm->createNewCategory($formData['name'], $formData['parent'], $formData['langCode']);

            return $this->get('response.category')->byCountry($formData['langCode'], 0);
        }

        return (new \AppBundle\Helpers\FormException(406, $form))->response();
    }

    /**
     * Add category
     *
     * @Route("/{categoryId}", name="category_patch")
     * @Method("PATCH")
     * @Rest\View(statusCode=202)
     */
    public function patchCategoryAction(Request $request, int $categoryId)
    {
        $form = $this->createForm(ProductCategoryType::class);

        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $cm = $this->get('manager.category');

            $cm->updateCategory($categoryId, $formData['name'], $formData['langCode']);

            return $this->get('response.category')->byCountry($formData['langCode'], 0);
        }

        return (new \AppBundle\Helpers\FormException(406, $form))->response();
    }

    /**
     * Delete category
     *
     * @Route("/{categoryId}/{langCode}", name="category_delete")
     * @Method("DELETE")
     * @Rest\View(statusCode=202)
     */
    public function deleteCategoryAction(Request $request, int $categoryId, string $langCode)
    {
        $cm = $this->get('manager.category');
        $category = $cm->findOneBy(['id' => $categoryId]);
        $cm->remove($category);
        return $this->get('response.category')->byCountry($langCode, 0);
    }
}
