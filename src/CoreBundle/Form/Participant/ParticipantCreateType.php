<?php
namespace CoreBundle\Form\Participant;

use CoreBundle\Entity\User;
use CoreBundle\Entity\Challenge;
use RestBundle\Form\AbstractFormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use CoreBundle\Model\Request\Participant\ParticipantCreateRequest;

/**
 * Class ParticipantCreateType
 * @package CoreBundle\Form\Participant
 */
class ParticipantCreateType extends AbstractFormType
{
    const DATA_CLASS = ParticipantCreateRequest::class;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'user', EntityType::class,
                [
                    'class' => User::class,
                ]
            )
            ->add(
                'challenge', EntityType::class,
                [
                    'class' => Challenge::class,
                ]
            );
    }
}