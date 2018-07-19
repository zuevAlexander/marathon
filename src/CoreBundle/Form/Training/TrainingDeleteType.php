<?php

namespace CoreBundle\Form\Training;

use CoreBundle\Entity\Training;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use CoreBundle\Model\Request\Training\TrainingDeleteRequest;
use RestBundle\Form\AbstractFormType;


/**
 * Class TrainingDeleteType
 */
class TrainingDeleteType extends AbstractFormType
{

    const DATA_CLASS = TrainingDeleteRequest::class;

    /**
     * @param FormEvent $event
     * @return void
     */
    public function preSubmit(FormEvent $event)
    {
        $event
            ->getForm()
            ->add('training', EntityType::class, [
                    'class' => Training::class,
                    'required' => true,
                    'invalid_message' => 'Training is not found',
                ]
            );
    }
}
