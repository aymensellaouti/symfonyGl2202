<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class Product extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i =0; $i<200;  $i++) {
            $product = new \App\Entity\Product();
            $product->setName("Product$i");
            $product->setDescription($faker->sentence);
            $product->setPrice($faker->numberBetween(5,500));
            $user = $i % 20;
            $product->setUser($this->getReference("user$user"));
            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
