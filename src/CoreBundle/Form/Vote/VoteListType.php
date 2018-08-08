<?php

namespace CoreBundle\Form\Vote;

use CoreBundle\Entity\Challenge;
use CoreBundle\Model\Request\Vote\VoteListRequest;
use RestBundle\Form\AbstractFormGetListType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ChallengeListType
 * @package CoreBundle\Form\Challenge
 */
class VoteListType extends AbstractFormGetListType
{
    const DATA_CLASS = VoteListRequest::class;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('challenge', EntityType::class, [
                    'class' => Challenge::class,
                    'required' => true,
                    'invalid_message' => 'Challenge is not found',
                ]
            );
    }
}
