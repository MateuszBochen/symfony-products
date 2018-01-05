<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueProductStorageGroupCombination extends Constraint
{
    public $message = 'This combination of product properties is alerty exist';

    public function validatedBy()
    {
        return 'unique-product-storage-group-combination';
    }
}
