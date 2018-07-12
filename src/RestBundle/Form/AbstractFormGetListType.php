<?php

namespace RestBundle\Form;

use Doctrine\Common\Collections\Criteria;
use RestBundle\Request\ListRequestInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AbstractFormGetListTyp.
 */
abstract class AbstractFormGetListType extends AbstractFormType
{
    const DATA_CLASS = ListRequestInterface::class;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('limit', IntegerType::class, [
                'required' => false,
            ])
            ->add('page', IntegerType::class, [
                'required' => false,
            ])
            ->add('sort', TextType::class, [
                'required' => false,
            ])
            ->add('includeDeleted', TextType::class, [
                'required' => false,
            ])
            ->add('order', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    Criteria::ASC,
                    Criteria::DESC,
                ],
                'choices_as_values' => true,
            ]);
    }
}
