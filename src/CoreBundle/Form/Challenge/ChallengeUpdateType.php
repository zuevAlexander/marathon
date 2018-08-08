<?php

namespace CoreBundle\Form\Challenge;

use CoreBundle\Entity\Status;
use CoreBundle\Form\Participant\ParticipantAddType;
use CoreBundle\Form\Participant\ParticipantCreateType;
use CoreBundle\Model\Request\Challenge\ChallengeUpdateRequest;
use RestBundle\Form\AbstractFormType;
use Symfony\Component\Form\FormBuilderInterface;
use CoreBundle\Entity\Challenge;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormEvent;

/**
 * Class ChallengeUpdateType
 * @package CoreBundle\Form\Challenge
 */
class ChallengeUpdateType extends AbstractFormType
{
    const DATA_CLASS = ChallengeUpdateRequest::class;

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

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('author', TextType::class, [
                'required' => false,
            ])
            ->add('description', TextType::class, [
                'required' => false,
            ])
            ->add('alias', TextType::class, [
                'required' => false,
            ])
            ->add('start_date', IntegerType::class, [
                'required' => true,
            ])
            ->add('end_date', IntegerType::class, [
                'required' => true,
            ])
            ->add('daily_goal', IntegerType::class, [
                'required' => true,
            ])
            ->add('challenge_goal', IntegerType::class, [
                'required' => true,
            ])
            ->add('challenge_goal', IntegerType::class, [
                'required' => true,
            ])
            ->add('status', EntityType::class,
                [
                    'class' => Status::class,
                ]
            )
            ->add('participants', CollectionType::class, [
                'entry_type' => ParticipantAddType::class,
                'allow_add' => true,
            ]);

        $this->registerPreSubmitEventListener($builder);
    }
}
