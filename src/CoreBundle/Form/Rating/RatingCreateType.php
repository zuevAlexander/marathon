<?php
namespace CoreBundle\Form\Rating;


use CoreBundle\Entity\Participant;
use RestBundle\Form\AbstractFormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use CoreBundle\Model\Request\Rating\RatingCreateRequest;

/**
 * Class RatingCreateType
 * @package CoreBundle\Form\Rating
 */
class RatingCreateType extends AbstractFormType
{
    const DATA_CLASS = RatingCreateRequest::class;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'participant', EntityType::class,
                [
                    'class' => Participant::class,
                ]
            )
            ->add('place', IntegerType::class);
    }
}