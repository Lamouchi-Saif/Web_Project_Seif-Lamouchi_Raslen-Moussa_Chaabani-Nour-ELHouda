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
        $product2 = new Product();
        $product2->setName('Pepperoni');
        $product2->setDescription('Spicy pepperoni slices with mozzarella cheese and tomato sauce.');
        $product2->setPrice('9.99');
        $product2->setImageUrl('images/pepperoni.jpg');
        $manager->persist($product2);
        $manager->flush();
        $product3 = new Product();
        $product3->setName('Vegetarian');
        $product3->setDescription('A mix of fresh vegetables with mozzarella cheese and tomato sauce.');
        $product3->setPrice('10.49');
        $product3->setImageUrl('images/vegetarian.jpg');
        $manager->persist($product3);
        $manager->flush();
    }
}
