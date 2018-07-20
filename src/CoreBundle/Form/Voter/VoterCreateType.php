<?php

namespace CoreBundle\Form\Voter;

use CoreBundle\Form\Vote\VoteCreateType;
use CoreBundle\Model\Request\Voter\VoterCreateRequest;
use RestBundle\Form\AbstractFormType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class VoterCreateType extends AbstractFormType
{
    const DATA_CLASS = VoterCreateRequest::class;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('voting_name', TextType::class, [
                'required' => false,
            ])
            ->add('voting_email', EmailType::class, [
                'required' => true,
            ])
            ->add('votes', CollectionType::class, [
                'entry_type' => VoteCreateType::class,
                'allow_add' => true,
            ]);
    }
}
