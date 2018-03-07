<?php

namespace UserBundle\Form\Type;


use UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;

class PasswordChangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('old', PasswordType::class, [
                'label' => 'form.profile.old.password',
                'mapped' => false,
                'constraints' => [
                    new UserPassword()
                ]
            ])
            ->add('new', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'form.profile.new.password',
                ],
                'second_options' => [
                    'label' => 'form.profile.repeat.password'
                ],
                'constraints' => [
                    new Assert\Regex('/^[A-Z a-z 0-9]{3}/')
                ]
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'form'
        ]);
    }

}