<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueSKU extends Constraint
{
    public $message = 'The SKU "{{ string }}" must be Unique per product.';

    public function validatedBy()
    {
        return 'sku';
    }
}
