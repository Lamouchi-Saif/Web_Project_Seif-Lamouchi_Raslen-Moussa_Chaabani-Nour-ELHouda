<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class IngredientFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $ingredient= new Ingredient();
        $ingredient->setName("pepper");
        $manager->persist($ingredient);

        $manager->flush();
    }
}
