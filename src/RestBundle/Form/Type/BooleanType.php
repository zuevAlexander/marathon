<?php

namespace RestBundle\Form\Type;

use RestBundle\Form\DataTransformer\BooleanTypeToBooleanTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BooleanType extends AbstractType
{
    const TYPE_YES = 1;

    const TYPE_NO = 0;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['transform']) {
            $builder->addModelTransformer(new BooleanTypeToBooleanTransformer());
        }

        if ($options['catalogue'] !== 'SonataCoreBundle') {
            @trigger_error('Option "catalogue" is deprecated since SonataCoreBundle 2.3.10 and will be removed in 4.0. Use option "translation_domain" instead.', E_USER_DEPRECATED);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $choices = array(
            self::TYPE_YES => 'label_type_yes',
            self::TYPE_NO => 'label_type_no',
        );

        $defaultOptions = array(
            'transform' => true,

            // @deprecated Deprecated as of SonataCoreBundle 2.3.10, to be removed in 4.0.
            'catalogue' => 'SonataCoreBundle',

            // Use directly translation_domain in SonataCoreBundle 4.0
            'translation_domain' => function (Options $options) {
                if ($options['catalogue']) {
                    return $options['catalogue'];
                }

                return $options['translation_domain'];
            },
        );

        // SF 2.7+ BC
        if (method_exists('Symfony\Component\Form\AbstractType', 'configureOptions')) {
            $choices = array_flip($choices);

            // choice_as_value options is not needed in SF 3.0+
            if (method_exists('Symfony\Component\Form\FormTypeInterface', 'setDefaultOptions')) {
                $defaultOptions['choices_as_values'] = true;
            }
        }

        $defaultOptions['choices'] = $choices;

        $resolver->setDefaults($defaultOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix') ?
            'Symfony\Component\Form\Extension\Core\Type\ChoiceType' :
            'choice' // SF <2.8 BC
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sonata_type_boolean';
    }
}
