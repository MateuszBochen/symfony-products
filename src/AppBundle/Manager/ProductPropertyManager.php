<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductProperty;
use AppBundle\Entity\ProductStorageGroupProperty;
use AppBundle\Repository\ProductPropertyLanguageRepository;
use AppBundle\Repository\ProductPropertyRepository;
use AppBundle\Repository\ProductPropertyValueLanguageRepository;
use AppBundle\Repository\ProductPropertyValueRepository;
use AppBundle\Repository\ProductStorageGroupPropertyRepository;
use AppBundle\Repository\ProductStorageGroupRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use \AppBundle\Entity\ProductPropertyValue;

class ProductPropertyManager extends BaseManager
{
    private $productPropertyLanguageRepository;
    private $productPropertyValueLanguageRepository;
    private $productPropertyValueRepository;
    private $productStorageGroupPropertyRepository;
    private $productStorageGroupRepository;

    public function __construct(
        ValidatorInterface $validator,
        ProductPropertyRepository $productPropertyRepository,
        ProductPropertyLanguageRepository $productPropertyLanguageRepository,
        ProductPropertyValueRepository $productPropertyValueRepository,
        ProductPropertyValueLanguageRepository $productPropertyValueLanguageRepository,
        ProductStorageGroupPropertyRepository $productStorageGroupPropertyRepository,
        ProductStorageGroupRepository $productStorageGroupRepository
    ) {
        $this->repository = $productPropertyRepository;
        $this->productPropertyLanguageRepository = $productPropertyLanguageRepository;
        $this->validator = $validator;
        $this->productPropertyValueLanguageRepository = $productPropertyValueLanguageRepository;
        $this->productPropertyValueRepository = $productPropertyValueRepository;
        $this->productStorageGroupPropertyRepository = $productStorageGroupPropertyRepository;
        $this->productStorageGroupRepository = $productStorageGroupRepository;
    }

    public function addProperty(Product $product, array $formData)
    {

        $propertyName = $formData['name'];
        $langCode = $formData['langCode'];
        $property = new ProductProperty();
        $property->setMainName($propertyName);
        $property->setIsStorageProperty($formData['isStorageProperty'] ? $formData['isStorageProperty'] : false);

        $propertyLanguage = new \AppBundle\Entity\ProductPropertyLanguage();
        $propertyLanguage->setLangCode($langCode);
        $propertyLanguage->setName($propertyName);

        $property->addLanguage($propertyLanguage);

        if (isset($formData['values']) && is_array($formData['values'])) {
            foreach ($formData['values'] as $value) {
                $ppv = $this->newPropertyValue($langCode, $value);
                $property->addValue($ppv);
            }
        }

        $product->addProperty($property);
    }

    public function addNewPropertyValue(int $propertyId, array $formData)
    {
        $this->currentEntity = $this->repository->findOneBy(['id' => $propertyId]);

        if (isset($formData['values']) && is_array($formData['values'])) {
            $langCode = $formData['langCode'];

            foreach ($formData['values'] as $value) {
                $ppv = $this->newPropertyValue($langCode, $value);
                $this->currentEntity->addValue($ppv);
            }
        }
    }

    public function updatePropertyValueLanguage(int $propertyId, array $formData)
    {
        $langCode = $formData['langCode'];
        $ppv = $this->productPropertyValueRepository->findOneBy(['id' => $propertyId]);
        $ppvl = $ppv->getLanguage($langCode);

        if ($ppvl) {
            $ppvl->setValue($formData['name']);
        } else {
            $ppvl = new \AppBundle\Entity\ProductPropertyValueLanguage();
            $ppvl->setLangCode($langCode);
            $ppvl->setValue($formData['name']);

            $ppv->addLanguage($ppvl);
        }

    }

    public function getLanguageById(int $id)
    {
        $this->currentEntity = $this->productPropertyLanguageRepository->findOneBy(['id' => $id]);
        return $this->currentEntity;
    }

    public function getPropertyById(int $id)
    {
        $this->currentEntity = $this->repository->findOneBy(['id' => $id]);
        return $this->currentEntity;
    }

    public function getPropertyValueLanguageById(int $id)
    {
        return $this->productPropertyValueLanguageRepository->findOneBy(['id' => $id]);
    }

    public function deletePropertyValue(int $propertyId)
    {
        $this->productPropertyValueRepository->deleteById($propertyId);
    }

    public function deleteProperty(int $propertyId)
    {
        $this->repository->deleteById($propertyId);
    }

    private function newPropertyValue($langCode, array $value): ProductPropertyValue
    {
        $ppv = new \AppBundle\Entity\ProductPropertyValue();
        $ppv->setMainValue($value['value']);

        $ppvl = new \AppBundle\Entity\ProductPropertyValueLanguage();
        $ppvl->setLangCode($langCode);
        $ppvl->setValue($value['value']);

        $ppv->addLanguage($ppvl);

        return $ppv;
    }

    public function toggleProductPropertyIsStoreProperty(int $productId, int $propertyId, ProductProperty $formData)
    {
        $newStatus = (bool) $formData->getIsStorageProperty();

        // change current proprety store status
        $this->getPropertyById($propertyId);
        $this->currentEntity->setIsStorageProperty($newStatus);
        $this->save();

        // if newStatus is false remove all properties from product storage group property
        // else add new storge property to all storage grups in this product
        if ($newStatus === false) {
            $this->productStorageGroupPropertyRepository->deleteAllByPropertyId($propertyId);
        } else {
            $groups = $this->productStorageGroupRepository->findBy(['product' => $productId]);
            if (!$groups) {
                return;
            }

            $property = $this->repository->findOneBy(['id' => $propertyId]);

            foreach ($groups as $productStorageGroup) {

                $productStorageGroupProperty = new ProductStorageGroupProperty();
                $productStorageGroupProperty->setProductProperty($property);

                $productStorageGroup->addProperty($productStorageGroupProperty);
                $this->productStorageGroupRepository->update($productStorageGroup);
            }
        }
    }
}
