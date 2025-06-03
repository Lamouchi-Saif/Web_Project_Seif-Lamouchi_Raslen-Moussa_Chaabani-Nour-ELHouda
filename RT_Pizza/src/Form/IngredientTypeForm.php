<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class IngredientTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Ingredient Name'
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Cheese' => 'cheese',
                    'Meat' => 'meat',
                    'Vegetable' => 'vegetable',
                    'Sauce' => 'sauce',
                    'Herb' => 'herb',
                    'Specialty' => 'specialty'
                ],
                'attr' => ['class' => 'form-control'],
                'label' => 'Category'
            ]);
        
        // Add the ingredientStock fields directly
        $builder->add('quantity', NumberType::class, [
            'mapped' => false,
            'attr' => ['class' => 'form-control'],
            'label' => 'Quantity'
        ]);
        
        $builder->add('unit', ChoiceType::class, [
            'mapped' => false,
            'choices' => [
                'Grams' => 'grams',
                'Kilograms' => 'kg',
                'Liters' => 'liters',
                'Milliliters' => 'ml',
                'Pieces' => 'pieces'
            ],
            'attr' => ['class' => 'form-control'],
            'label' => 'Unit'
        ]);
        
        $builder->add('price', NumberType::class, [
            'mapped' => false,
            'attr' => ['class' => 'form-control'],
            'label' => 'Price (per unit)',
            'scale' => 2,
            'html5' => true
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}