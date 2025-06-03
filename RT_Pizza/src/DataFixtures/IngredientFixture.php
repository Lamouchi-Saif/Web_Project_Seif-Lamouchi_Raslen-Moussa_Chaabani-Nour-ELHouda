<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\IngredientStock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class IngredientFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $ingredients = [
            'cheeses' => [
                'mozzarella',
                'parmesan',
                'feta'
            ],
            'meats' => [
                'pepperoni',
                'italian_sausage',
                'bacon',
                'ham'
            ],
            'vegetables' => [
                'mushrooms',
                'bell_peppers',
                'red_onions',
                'black_olives',
                'jalapenos',
                'spinach'
            ],
            'specialty' => [
                'pineapple',
                'artichoke_hearts',
                'sun_dried_tomatoes'
            ],
            'sauces' => [
                'tomato_sauce',
                'alfredo_sauce',
                'bbq_sauce'
            ],
            'herbs' => [
                'basil',
                'oregano'
            ]
        ];
        for ($i = 1; $i <= 20; $i++) {
            $ingredient = new Ingredient();
            if($i<=3){
                $ingredient->setName($ingredients['cheeses'][$i - 1]);
                $ingredient->setType('cheese');
            } elseif ($i <= 7) {
                $ingredient->setName($ingredients['meats'][$i - 4]);
                $ingredient->setType('meat');
            } elseif ($i <= 13) {
                $ingredient->setName($ingredients['vegetables'][$i - 8]);
                $ingredient->setType('vegetable');
            } elseif ($i <= 16) {
                $ingredient->setName($ingredients['specialty'][$i - 14]);
                $ingredient->setType('specialty');
            } elseif ($i <= 18) {
                $ingredient->setName($ingredients['sauces'][$i - 17]);
                $ingredient->setType('sauce');
            } else {
                $ingredient->setName($ingredients['herbs'][$i - 19]);
                $ingredient->setType('herb');
            }

            // Create corresponding IngredientStock
            $ingredientStock = new IngredientStock();
            $ingredient->setIngredientStock($ingredientStock);
            $ingredientStock->setIngredient($ingredient);
            $ingredientStock->setQuantity(mt_rand(5000, 10000)); // Random quantity between 10 and 100
            $ingredientStock->setPrice(mt_rand(100,200));
            if($ingredient->getType() === 'sauce') {
                $ingredientStock->setUnit('liters');
            } else {
                $ingredientStock->setUnit('grams');
            }
            $manager->persist($ingredient);
            $manager->persist($ingredientStock);
        }
        $manager->flush();
    }
}
