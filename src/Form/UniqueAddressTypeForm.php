<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UniqueAddressTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Adresse',
                    'class' => 'form-control',
                ],
                'row_attr' => ['class' => 'mb-3'], // marge entre les champs
            ])
            ->add('city', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ville',
                    'class' => 'form-control',
                ],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('postaleCode', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Code postal',
                    'class' => 'form-control',
                ],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('country', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Pays',
                    'class' => 'form-control',
                ],
                'row_attr' => ['class' => 'mb-3'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
