<?php

namespace CoreBundle\Form\Challenge;

use CoreBundle\Entity\Status;
use CoreBundle\Form\Participant\ParticipantAddType;
use CoreBundle\Model\Request\Challenge\ChallengeCreateRequest;
use RestBundle\Form\AbstractFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * Class ChallengeCreateType
 * @package CoreBundle\Form\Challenge
 */
class ChallengeCreateType extends AbstractFormType
{
    const DATA_CLASS = ChallengeCreateRequest::class;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
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
    }
}
