<?php

namespace CoreBundle\Form\User;

use CoreBundle\Model\Request\User\UserReadRequest;
use RestBundle\Form\AbstractFormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class UserReadType extends AbstractFormType
{
    const DATA_CLASS = UserReadRequest::class;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', IntegerType::class, [
                'required' => true,
            ]);
    }
}
