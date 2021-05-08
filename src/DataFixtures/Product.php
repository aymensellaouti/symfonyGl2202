<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Provider\Address;

class Product extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i =0; $i<20;  $i++) {
            $product = new \App\Entity\Product();
            $product->setName("Product$i");
            $product->setDescription($faker->sentence);
            $product->setPrice($faker->numberBetween(5,500));
            $manager->persist($product);
        }

        $manager->flush();
    }
}
