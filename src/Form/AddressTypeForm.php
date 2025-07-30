<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', TextType::class, [
                'label' => 'Rue',
                'required' => true,
            ])
            ->add('postal_code', TextType::class, [   // PostalCodeType si tu l'utilises
                'label' => 'Code postal',
                'required' => true,
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'required' => true,
            ])
            ->add('country', CountryType::class, [
                'label' => 'Pays',
                'required' => true,
                'placeholder' => 'Choisissez un pays',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Mets ici l'entité supportée si tu en as une, sinon null ou une DTO
        $resolver->setDefaults([
            // 'data_class' => Address::class,
            'data_class' => null,
        ]);
    }
}
