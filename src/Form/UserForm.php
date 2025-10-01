<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Client' => 'ROLE_CLIENT',
                ],
                'expanded' => false,
                'multiple' => true,
                'label' => 'Rôles',
                'required' => true,
            ])
            ->add('isVerified', CheckboxType::class, [
                'label' => 'Compte vérifié',
                'required' => false,
            ])
            ->add('password', PasswordType::class)
            ->add('createdAt', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de création',
                'required' => false,
            ])
            ->add('verifiedAt', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de vérification',
                'required' => false,
            ])
            ->add('updatedAt', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de mise à jour',
                'required' => false,
            ])
            ->add('phoneNumber')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
