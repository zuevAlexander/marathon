<?php

namespace CoreBundle\Form\User;

use CoreBundle\Model\Request\User\UserRegisterRequest;
use RestBundle\Form\AbstractFormType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserRegisterType extends AbstractFormType
{
    const DATA_CLASS = UserRegisterRequest::class;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'required' => true,
            ])
            ->add('password', PasswordType::class, array(
                'required' => false,
            ));
    }
}
