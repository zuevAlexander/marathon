<?php

namespace CoreBundle\Form\User;

use CoreBundle\Model\Request\User\UserUpdateRequest;
use RestBundle\Form\AbstractFormType;
use Symfony\Component\Form\FormBuilderInterface;
use CoreBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use RestBundle\Form\TextType;


/**
 * Class UserUpdateType
 * @package CoreBundle\Form\User
 */
class UserUpdateType extends AbstractFormType
{
    const DATA_CLASS = UserUpdateRequest::class;

    /**
     * @param FormEvent $event
     * @return void
     */
    public function preSubmit(FormEvent $event)
    {
        $event
            ->getForm()
            ->add('user', EntityType::class, [
                    'class' => User::class,
                    'required' => true,
                    'invalid_message' => 'User is not found',
                ]
            );
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'by_reference' => false
            ])
            ->add('email', TextType::class, [
                'by_reference' => false
            ]);

        $this->registerPreSubmitEventListener($builder);
    }
}
