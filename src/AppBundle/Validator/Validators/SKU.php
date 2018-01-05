<?php

namespace AppBundle\Validator\Validators;

use AppBundle\Repository\ProductRepository;
use AppBundle\Repository\ProductStorageGroupRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SKU extends ConstraintValidator
{
    protected $productRepository;
    protected $productStorageGroupRepository;

    public function __construct(ProductRepository $productRepository, ProductStorageGroupRepository $productStorageGroupRepository)
    {
        $this->productRepository = $productRepository;
        $this->productStorageGroupRepository = $productStorageGroupRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$value) {
            return;
        }

        $one = $this->productRepository->findOneBy(['sku' => $value]);
        $two = $this->productStorageGroupRepository->findOneBy(['sku' => $value]);
        $current = $this->context->getObject();

        if (($this->isProduct($current) && $two) || ($this->isProductStorageGroup($current) && $one)) {
            $this->myBuildViolation($constraint, $value);
        }

        if (($this->isProduct($current) && $one && $current->getId() != $one->getId()) || (
            $this->isProductStorageGroup($current) && $two && $current->getId() != $two->getId()
        )) {
            $this->myBuildViolation($constraint, $value);
        }
    }

    protected function myBuildViolation(Constraint $constraint, $value)
    {
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $value)
            ->addViolation();
    }

    private function isProduct($currentOject)
    {
        return get_class($currentOject) === 'AppBundle\Entity\Product';
    }

    private function isProductStorageGroup($currentOject)
    {
        return get_class($currentOject) === 'AppBundle\Entity\ProductStorageGroup';
    }
}
