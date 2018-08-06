<?php

namespace CoreBundle\Form\Challenge;

use CoreBundle\Entity\Challenge;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use CoreBundle\Model\Request\Challenge\ChallengeDeleteRequest;
use RestBundle\Form\AbstractFormType;


/**
 * Class ChallengeDeleteType
 * @package CoreBundle\Form\Challenge
 */
class ChallengeDeleteType extends AbstractFormType
{
    const DATA_CLASS = ChallengeDeleteRequest::class;

    /**
     * @param FormEvent $event
     * @return void
     */
    public function preSubmit(FormEvent $event)
    {
        $event
            ->getForm()
            ->add('challenge', EntityType::class, [
                    'class' => Challenge::class,
                    'required' => true,
                    'invalid_message' => 'Challenge is not found',
                ]
            );
    }
}
