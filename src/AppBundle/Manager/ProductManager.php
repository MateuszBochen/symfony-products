<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductLanguage;
use AppBundle\Entity\ProductProperty;
use AppBundle\Entity\ProductPropertyValue;
use AppBundle\Repository\ProductPropertyRepository;
use AppBundle\Repository\ProductPropertyValueRepository;
use AppBundle\Repository\ProductRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductManager extends BaseManager
{
    private $productPropertyValueRepository;
    private $productPropertyRepository;

    public function __construct(
        ProductRepository $productRepository,
        ValidatorInterface $validator,
        ProductPropertyRepository $productPropertyRepository,
        ProductPropertyValueRepository $productPropertyValueRepository
    ) {
        $this->repository = $productRepository;
        $this->validator = $validator;
        $this->productPropertyValueRepository = $productPropertyValueRepository;
        $this->productPropertyRepository = $productPropertyRepository;
    }

    public function getNewProduct(): Product
    {
        $this->currentEntity = new Product();
        $this->setDefaultValues();

        return $this->currentEntity;
    }

    public function findOneBy(array $conditions): Product
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

    public function addProperty(ProductProperty $property)
    {
        $errors = $this->validator->validate($property);

        if ($errors->count()) {
            throw new ManagerValidationException($errors);
        }

        $this->currentEntity->addProperty($property);
    }

    public function removeProperty(ProductProperty $property)
    {
        $this->currentEntity->removeProperty($property);
    }

    public function removePropertyValue(int $propertyValueId)
    {
        $this->productPropertyValueRepository->deleteById($propertyValueId);
    }

    public function addPropertyValue(ProductPropertyValue $productPropertyValue, int $propertyId)
    {
        $property = $this->productPropertyRepository->findOneBy(['id' => $propertyId]);
        $property->addValue($productPropertyValue);
        $this->productPropertyRepository->update($property);
    }

    /*public function getProperty(int $propertyId)
    {
    return $this->productPropertyRepository->findOneBy(['id' => $propertyId]);
    }*/

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
