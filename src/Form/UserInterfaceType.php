<?php
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

// ...
$builder->add('roles', ChoiceType::class, [
    'label' => 'Rôle',
    'choices'  => [
        'Administrateur' => 'ROLE_ADMIN',
        'Client' => 'ROLE_CLIENT'
    ],
    'expanded' => false,
    'multiple' => true,
    'required' => true,
]);
