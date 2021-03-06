<?php

namespace CoreBundle\Form\Vote;

use CoreBundle\Entity\Challenge;
use CoreBundle\Form\Rating\RatingCreateType;
use CoreBundle\Model\Request\Vote\VoteCreateRequest;
use RestBundle\Form\AbstractFormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * Class VoteCreateType
 * @package CoreBundle\Form\Vote
 */
class VoteCreateType extends AbstractFormType
{
    const DATA_CLASS = VoteCreateRequest::class;

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
            )
            ->add('ratings', CollectionType::class, [
                'entry_type' => RatingCreateType::class,
                'allow_add' => true,
            ]);
    }
}
