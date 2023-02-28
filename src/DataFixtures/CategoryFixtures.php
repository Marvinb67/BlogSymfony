<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');

        for ($i = 0; $i < 10; $i++)
        {
            $category = new Category;
            $category
                ->setColor($faker->safeColorName())
                ->setName($faker->words(3, true))
            ;
            $manager->persist($category);
        }

        $manager->flush();

        $manager->flush();
    }
}
