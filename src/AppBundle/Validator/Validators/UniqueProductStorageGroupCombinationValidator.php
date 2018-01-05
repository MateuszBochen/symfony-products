<?php

namespace AppBundle\Validator\Validators;

use AppBundle\Repository\ProductStorageGroupPropertyRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueProductStorageGroupCombinationValidator extends ConstraintValidator
{
    protected $productStorageGroupPropertyRepository;

    public function __construct(ProductStorageGroupPropertyRepository $productStorageGroupPropertyRepository)
    {
        $this->productStorageGroupPropertyRepository = $productStorageGroupPropertyRepository;
    }

    public function validate($productStorageGroupPropertyCollection, Constraint $constraint)
    {
        $collections = [];

        foreach ($productStorageGroupPropertyCollection as $productStorageGroupProperty) {

            $id = $productStorageGroupProperty->getId();
            $propertyId = $productStorageGroupProperty->getProductProperty()->getId();
            $propertyValueId = $productStorageGroupProperty->getProductPropertyValue()->getId();

            $results = $this->productStorageGroupPropertyRepository->findBy([
                'productProperty' => $propertyId,
                'productPropertyValue' => $propertyValueId,
            ]);

            foreach ($results as $result) {
                $groupId = $result->getProductStorageGroup()->getId();
                if ($result->getId() !== $id) {
                    $collections[$groupId][] = true;
                }
            }
        }

        foreach ($collections as $collection) {
            if (count($collection) === count($productStorageGroupPropertyCollection)) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
                break;
            }
        }
    }
}
