<?php
namespace App\Form;

use App\Entity\IngredientStock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class IngredientStockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Quantity'
            ])
            ->add('unit', ChoiceType::class, [
                'choices' => [
                    'Grams' => 'grams',
                    'Kilograms' => 'kg',
                    'Liters' => 'liters',
                    'Milliliters' => 'ml',
                    'Pieces' => 'pieces'
                ],
                'attr' => ['class' => 'form-control'],
                'label' => 'Unit'
            ])
            ->add('price', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Price (per unit)',
                'scale' => 2,
                'html5' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => IngredientStock::class,
        ]);
    }
}