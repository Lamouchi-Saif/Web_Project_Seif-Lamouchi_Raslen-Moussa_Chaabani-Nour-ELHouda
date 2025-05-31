<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product();
        $product->setName('Margherita');
        $product->setDescription('Classic pizza with tomato sauce, mozzarella cheese, and fresh basil.');
        $product->setPrice('8.99');
        $product->setImageUrl('images/margherita.jpg');
        $manager->persist($product);
        $manager->flush();
    }
}
