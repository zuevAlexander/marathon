<?php

namespace RestBundle\Form\DataTransformer;

use RestBundle\Form\Type\BooleanType;
use Symfony\Component\Form\DataTransformerInterface;

class BooleanTypeToBooleanTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if ($value === true or (int) $value === BooleanType::TYPE_YES) {
            return BooleanType::TYPE_YES;
        }

        return BooleanType::TYPE_NO;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if ($value === BooleanType::TYPE_YES) {
            return true;
        }

        return false;
    }
}
