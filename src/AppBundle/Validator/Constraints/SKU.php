<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Repository\ProductPropertyValueRepository;
use AppBundle\Repository\ProductRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SKU extends ConstraintValidator
{
    public function __construct(ProductRepository $productRepository, ProductPropertyValueRepository $productPropertyValueRepository)
    {
        $this->productRepository = $productRepository;
        $this->productPropertyValueRepository = $productPropertyValueRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        $one = $this->productRepository->findOneBy(['sku' => $value]);
        $current = $this->context->getObject();

        if ($one && ($current->getId() != $one->getId())) {
            $this->myBuildViolation($constraint, $value);
        }

        /*$two = $this->productPropertyValueRepository->findOneBy(['propertySku' => $value]);
    if ($two) {
    $this->myBuildViolation($constraint, $value);
    }*/
    }

    private function myBuildViolation(Constraint $constraint, $value)
    {
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $value)
            ->addViolation();
    }
}
