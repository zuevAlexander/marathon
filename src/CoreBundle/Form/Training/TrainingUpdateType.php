<?php

namespace CoreBundle\Form\Training;

use CoreBundle\Model\Request\Training\TrainingUpdateRequest;
use RestBundle\Form\AbstractFormType;
use Symfony\Component\Form\FormBuilderInterface;
use CoreBundle\Entity\Training;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


/**
 * Class TrainingUpdateType
 * @package CoreBundle\Form\Training
 */
class TrainingUpdateType extends AbstractFormType
{
    const DATA_CLASS = TrainingUpdateRequest::class;

    /**
     * @param FormEvent $event
     * @return void
     */
    public function preSubmit(FormEvent $event)
    {
        $event
            ->getForm()
            ->add('training', EntityType::class, [
                    'class' => Training::class,
                    'required' => true,
                    'invalid_message' => 'Training is not found',
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
            ->add('amount', IntegerType::class, [
                'by_reference' => false
            ]);

        $this->registerPreSubmitEventListener($builder);
    }
}
