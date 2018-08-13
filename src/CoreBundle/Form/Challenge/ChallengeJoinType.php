<?php

namespace CoreBundle\Form\Challenge;

use CoreBundle\Entity\Challenge;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use CoreBundle\Model\Request\Challenge\ChallengeJoinRequest;
use RestBundle\Form\AbstractFormType;


/**
 * Class ChallengeJoinType
 * @package CoreBundle\Form\Challenge
 */
class ChallengeJoinType extends AbstractFormType
{
    const DATA_CLASS = ChallengeJoinRequest::class;

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
