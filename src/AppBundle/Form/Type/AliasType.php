<?php

namespace AppBundle\Form\Type;


use AppBundle\Entity\Alias;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AliasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'alias.name'
            ])
            ->add('origin', null, [
                'label' => 'alias.origin'
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Alias::class,
            'translation_domain' => 'alias'
        ]);
    }

}