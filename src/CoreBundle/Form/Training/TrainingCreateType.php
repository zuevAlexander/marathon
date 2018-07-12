<?php

namespace CoreBundle\Form\Training;

use CoreBundle\Model\Request\Training\TrainingCreateRequest;
use RestBundle\Form\AbstractFormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class TrainingCreateType extends AbstractFormType
{
    const DATA_CLASS = TrainingCreateRequest::class;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', IntegerType::class, [
                'required' => true,
            ])
            ->add('day', IntegerType::class, [
                'required' => true,
            ])
            ->add('user', IntegerType::class, [
                'required' => true,
            ]);
    }
}
