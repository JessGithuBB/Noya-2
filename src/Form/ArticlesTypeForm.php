<?php

namespace App\Form;

use App\Entity\Articles;
use App\Form\ArticleImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ArticlesTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'Code',
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug',
            ])
            ->add('short_description', TextareaType::class, [
                'label' => 'Description courte',
            ])
            ->add('long_description', TextareaType::class, [
                'label' => 'Description longue',
                'required' => false,
            ])
            ->add('brand', TextType::class, [
                'label' => 'Marque',
            ])
            ->add('selling_price', NumberType::class, [
                'label' => 'Prix de vente',
                'scale' => 2,
            ])
            ->add('quantity', TextType::class, [
                'label' => 'Quantité',
            ])
            ->add('is_new_arrival', CheckboxType::class, [
                'label' => 'Nouveauté',
                'required' => false,
            ])
            ->add('is_best_seller', CheckboxType::class, [
                'label' => 'Best Seller',
                'required' => false,
            ])
            ->add('is_available', CheckboxType::class, [
                'label' => 'Disponible',
                'required' => false,
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => ArticleImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image de l’article',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (jpg, png, webp)',
                    ]),
                ],
            ])
            ->add('keywordsText', TextType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Mots-clés (séparés par des virgules)',
                'attr' => [
                'placeholder' => 'ex: été, promo, coton',
                'class' => 'form-control',
            ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
