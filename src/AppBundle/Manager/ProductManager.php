<?php

namespace AppBundle\Manager;

use AppBundle\Repository\ProductRepository;
use AppBundle\Entity\Product;
use AppBundle\Entity\Category;
use AppBundle\Entity\ProductLanguage;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductManager extends BaseManager
{

    public function __construct(ProductRepository $productRepository, ValidatorInterface $validator)
    {
        $this->repository = $productRepository;
        $this->validator = $validator;
    }

    public function getNewProduct():Product
    {
        $this->currentEntity = new Product();
        $this->setDefaultValues();

        return $this->currentEntity;
    }

    public function findOneBy(array $conditions):Product
    {
        $this->currentEntity = $this->repository->findOneBy($conditions);

        return $this->currentEntity;
    }

    public function addLanguage(ProductLanguage $language)
    {
        $errors = $this->validator->validate($language);

        if ($errors->count()) {
            throw new ManagerValidationException($errors);
        }

        $this->currentEntity->addLanguage($language);
    }

    public function removeLanguage(ProductLanguage $productLanguage)
    {
        $this->currentEntity->removeLanguage($productLanguage);
    }

    public function addProperty(ProductProperties $property)
    {
        $errors = $this->validator->validate($property);

        if ($errors->count()) {
            throw new ManagerValidationException($errors);
        }

        $this->currentEntity->addProperty($property);
    }

    public function removeProperty(ProductProperties $property)
    {
        $this->currentEntity->removeProperty($property);
    }

    public function addCategory(Category $category)
    {
        $errors = $this->validator->validate($category);

        if ($errors->count()) {
            throw new ManagerValidationException($errors);
        }

        $this->currentEntity->addCategory($category);
    }

    public function removeCategory(Category $category)
    {
        $this->currentEntity->removeCategory($category);
    }

    private function setDefaultValues()
    {
        $this->currentEntity->setCategories(0)
            ->setWidth(0)
            ->setLength(0)
            ->setWeight(0)
            ->setDimensionUnit('cm')
            ->setWeightUnit('kg')
            ->setHeight(0);
    }
}
