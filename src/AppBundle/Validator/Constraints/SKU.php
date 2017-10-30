<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use AppBundle\Repository\ProductRepository;


class SKU extends ConstraintValidator
{
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        $one = $this->productRepository->findOneBy(['sku' => $value]);
        $current = $this->context->getObject();

        //var_dump($current->getId() != $one->getId()); exit;

        if ($one && ($current->getId() != $one->getId())) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
