<?php

namespace CoreBundle\Form\Participant;

use CoreBundle\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use CoreBundle\Model\Request\Participant\ParticipantDeleteRequest;
use RestBundle\Form\AbstractFormType;

/**
 * Class ParticipantDeleteType
 * @package CoreBundle\Form\Participant
 */
class ParticipantDeleteType extends AbstractFormType
{
    const DATA_CLASS = ParticipantDeleteRequest::class;

    /**
     * @param FormEvent $event
     * @return void
     */
    public function preSubmit(FormEvent $event)
    {
        $event
            ->getForm()
            ->add('participant', EntityType::class, [
                    'class' => Participant::class,
                    'required' => true,
                    'invalid_message' => 'Participant is not found',
                ]
            );
    }
}
