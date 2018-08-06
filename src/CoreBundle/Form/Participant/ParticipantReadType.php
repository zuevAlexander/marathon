<?php

namespace CoreBundle\Form\Participant;

use CoreBundle\Entity\Participant;
use CoreBundle\Model\Request\Participant\ParticipantReadRequest;
use RestBundle\Form\AbstractFormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ParticipantReadType
 * @package CoreBundle\Form\Participant
 */
class ParticipantReadType extends AbstractFormType
{
    const DATA_CLASS = ParticipantReadRequest::class;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('participant', EntityType::class,
                [
                    'class' => Participant::class,
                ]
            );
    }
}
