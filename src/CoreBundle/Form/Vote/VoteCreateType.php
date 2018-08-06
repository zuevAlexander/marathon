<?php
namespace CoreBundle\Form\Vote;


use CoreBundle\Entity\User;
use RestBundle\Form\AbstractFormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use CoreBundle\Model\Request\Vote\VoteCreateRequest;

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
            ->add(
                'user', EntityType::class,
                [
                    'class' => User::class,
                ]
            )
            ->add('place', IntegerType::class);
    }
}