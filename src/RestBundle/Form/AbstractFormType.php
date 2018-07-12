<?php

namespace RestBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractFormType.
 */
abstract class AbstractFormType extends AbstractType
{
    const DATA_CLASS = '';
    const CSRF_PROTECTION = false;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->registerPreSubmitEventListener($builder);
    }

    /**
     * @param FormBuilderInterface $builder
     */
    protected function registerPreSubmitEventListener(FormBuilderInterface $builder)
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'preSubmit']);
        $builder->addEventListener(FormEvents::POST_SUBMIT, [$this, 'postSubmit']);
    }

    /**
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event)
    {
    }

    /**
     * @param FormEvent $event
     */
    public function postSubmit(FormEvent $event)
    {
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->getDataClass(),
            'csrf_protection' => static::CSRF_PROTECTION,
        ]);
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    private function getDataClass() : string
    {
        if (empty(static::DATA_CLASS)) {
            throw new \Exception('You should define const DATA_CLASS for class '.static::class);
        }

        return static::DATA_CLASS;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param string               $child
     * @param string               $class
     * @param bool                 $required
     * @param string               $label
     */
    protected function addMultipleChildEntities(
        FormBuilderInterface $builder,
        string $child,
        string $class,
        bool $required = true,
        string $label = ''
    ) {
        $builder->add(
            $child,
            EntityType::class,
            [
                'class' => $class,
                'multiple' => true,
                'label' => $label,
                'required' => $required,
            ]
        );
    }
}
