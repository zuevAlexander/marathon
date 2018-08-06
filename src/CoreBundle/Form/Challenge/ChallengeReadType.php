<?php

namespace CoreBundle\Form\Challenge;

use CoreBundle\Entity\Challenge;
use CoreBundle\Model\Request\Challenge\ChallengeReadRequest;
use RestBundle\Form\AbstractFormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ChallengeReadType
 * @package CoreBundle\Form\Challenge
 */
class ChallengeReadType extends AbstractFormType
{
    const DATA_CLASS = ChallengeReadRequest::class;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('challenge', EntityType::class,
                [
                    'class' => Challenge::class,
                ]
            );
    }
}
