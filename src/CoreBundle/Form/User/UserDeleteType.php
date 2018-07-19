<?php

namespace CoreBundle\Form\User;

use CoreBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use CoreBundle\Model\Request\User\UserDeleteRequest;
use RestBundle\Form\AbstractFormType;


/**
 * Class UserDeleteType
 */
class UserDeleteType extends AbstractFormType
{

    const DATA_CLASS = UserDeleteRequest::class;

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
}
