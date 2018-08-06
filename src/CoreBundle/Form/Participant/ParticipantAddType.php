<?php
namespace CoreBundle\Form\Participant;

use CoreBundle\Entity\User;
use RestBundle\Form\AbstractFormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use CoreBundle\Model\Request\Participant\ParticipantAddRequest;

/**
 * Class ParticipantAddType
 * @package CoreBundle\Form\Participant
 */
class ParticipantAddType extends AbstractFormType
{
    const DATA_CLASS = ParticipantAddRequest::class;

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
            );
    }
}