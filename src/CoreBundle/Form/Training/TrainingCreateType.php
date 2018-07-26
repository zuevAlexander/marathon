<?php

namespace CoreBundle\Form\Training;

use CoreBundle\Entity\User;
use CoreBundle\Model\Request\Training\TrainingCreateRequest;
use RestBundle\Form\AbstractFormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

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
            ->add('user', EntityType::class,
                [
                    'class' => User::class,
                ]
            );
    }
}
