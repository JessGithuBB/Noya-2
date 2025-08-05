<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserInterfaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôle',
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Client' => 'ROLE_CLIENT',
                ],
                'expanded' => false, // false = liste déroulante ; true = cases à cocher
                'multiple' => true,  // true = possibilité de sélectionner plusieurs rôles
                'required' => true,
                'placeholder' => 'Sélectionner un rôle', // optionnel
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => User::class, // à décommenter si ce formulaire est lié à l'entité User
        ]);
    }
}
