<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Product;
use AppBundle\Repository\ProductPropertyLanguageRepository;
use AppBundle\Repository\ProductPropertyRepository;
use AppBundle\Repository\ProductPropertyValueLanguageRepository;
use AppBundle\Repository\ProductPropertyValueRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use \AppBundle\Entity\ProductPropertyValue;

class ProductPropertyManager extends BaseManager
{
    private $productPropertyLanguageRepository;
    private $productPropertyValueLanguageRepository;

    public function __construct(
        ValidatorInterface $validator,
        ProductPropertyRepository $productPropertyRepository,
        ProductPropertyLanguageRepository $productPropertyLanguageRepository,
        ProductPropertyValueRepository $productPropertyValueRepository,
        ProductPropertyValueLanguageRepository $productPropertyValueLanguageRepository
    ) {
        $this->repository = $productPropertyRepository;
        $this->productPropertyLanguageRepository = $productPropertyLanguageRepository;
        $this->validator = $validator;
        $this->productPropertyValueLanguageRepository = $productPropertyValueLanguageRepository;
        $this->productPropertyValueRepository = $productPropertyValueRepository;
    }

    public function addProperty(Product $product, array $formData)
    {

        $propertyName = $formData['name'];
        $langCode = $formData['langCode'];

        $property = new \AppBundle\Entity\ProductProperty();
        $property->setMainName($propertyName);

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
        return $this->productPropertyLanguageRepository->findOneBy(['id' => $id]);
    }
    public function getPropertyById(int $id)
    {
        return $this->repository->findOneBy(['id' => $id]);
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
}
