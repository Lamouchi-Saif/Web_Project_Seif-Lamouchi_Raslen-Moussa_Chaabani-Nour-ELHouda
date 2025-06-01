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
        $stock = new IngredientStock();
        $stock->setQuantity(500);
        $stock->setUnit('grams');
        $stock->setPrice('1.5');

        $manager->persist($stock);

        $ingredient = new Ingredient();
        $ingredient->setName('Mozzarella');
        $ingredient->setType('meat');
        $ingredient->setIngredientStock($stock);

        $manager->persist($ingredient);

        $manager->flush();
    }
}
