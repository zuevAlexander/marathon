<?php

namespace RestBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType as Base;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TextType
 */
class TextType extends Base implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ('' === $options['empty_data'] || $options['empty_data'] instanceof \Closure) {
            $builder->addViewTransformer($this);
        }
        parent::buildForm($builder, $options);
        // When empty_data is explicitly set to an empty string,
        // a string should always be returned when NULL is submitted
        // This gives more control and thus helps preventing some issues
        // with PHP 7 which allows type hinting strings in functions
        // See https://github.com/symfony/symfony/issues/5906#issuecomment-203189375
    }

    /**
     * {@inheritdoc}
     */
    public function transform($data)
    {
        // Model data should not be transformed
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($data)
    {
        return null === $data ? '' : $data;
    }
}
