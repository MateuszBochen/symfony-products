<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductFile;
use AppBundle\Entity\ProductImage;
use AppBundle\Entity\ProductLanguage;
use AppBundle\Manager\ProductFileManager;
use AppBundle\Manager\ProductImageManager;
use AppBundle\Manager\ProductPropertyManager;
use AppBundle\Repository\ProductRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductManager extends BaseManager
{
    private $productImageManager;
    private $productPropertyManager;
    private $productFileManager;

    public function __construct(
        ProductRepository $productRepository,
        ValidatorInterface $validator,
        ProductImageManager $productImageManager,
        ProductFileManager $productFileManager,
        ProductPropertyManager $productPropertyManager

    ) {
        $this->repository = $productRepository;
        $this->validator = $validator;
        // $this->productPropertyValueRepository = $productPropertyValueRepository;
        // $this->productPropertyRepository = $productPropertyRepository;
        $this->productImageManager = $productImageManager;
        $this->productFileManager = $productFileManager;
        $this->productPropertyManager = $productPropertyManager;
    }

    public function getNewProduct(): Product
    {
        $this->currentEntity = new Product();
        $this->setDefaultValues();

        return $this->currentEntity;
    }

    public function getProductBy(array $conditions): Product
    {
        return $this->findOneBy($conditions);
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

    public function addProperty($formData)
    {
        $this->productPropertyManager->addProperty($this->currentEntity, $formData);
    }

    public function getProductPropertyManager()
    {
        return $this->productPropertyManager; // ->getLanguageById($productPropertyLanguageId);
    }

    public function addCategory(Category $category)
    {
        $errors = $this->validator->validate($category);

        if ($errors->count()) {
            throw new ManagerValidationException($errors);
        }

        return $this->currentEntity->addCategory($category);
    }

    public function removeCategory(Category $category)
    {
        $this->currentEntity->removeCategory($category);
    }

    public function getProductImageManager()
    {
        return $this->productImageManager;
    }

    public function getProductFileManager()
    {
        return $this->productFileManager;
    }

    public function addNewImage(ProductImage $productImage)
    {
        $this->currentEntity->addImage($productImage);
        $this->productImageManager->addNewImage($productImage);
    }

    public function updateImage(ProductImage $productImage)
    {
        $this->productImageManager->updateImage($productImage);
    }

    public function addNewFile(ProductFile $file)
    {
        $this->currentEntity->addFile($file);
        $this->productFileManager->addNewFile($file);
    }

    public function updateFile(ProductFile $file)
    {
        $this->currentEntity = $file->getProduct();
        $this->productFileManager->updateFile($file);
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
