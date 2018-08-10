<?php

namespace CoreBundle\Form\LDAPAuthorization;

use CoreBundle\Model\Request\LDAPAuthorization\LDAPAuthorizationPostRequest;
use RestBundle\Form\AbstractFormType;
use RestBundle\Form\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LDAPAuthorizationPostType extends AbstractFormType
{
    const DATA_CLASS = LDAPAuthorizationPostRequest::class;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'by_reference'=>false,
            'required'=>true
        ])->add('password', TextType::class, [
            'by_reference'=>false,
            'required'=>true
        ]);
    }
}
