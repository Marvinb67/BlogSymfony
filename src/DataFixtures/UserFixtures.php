<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    const AUTHOR = 'author';

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {}
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');

        $userAdmin = new User();
        $userAdmin
            ->setEmail('admin@email.com')
            ->setPassword($this->passwordHasher->hashPassword($userAdmin, 'admin'))
            ->setUsername($faker->userName())
            ->setRoles(['ROLE_ADMIN'])
            ->setIsActive(1)
        ;

        $userAuthor = new User();

        $userAuthor
            ->setEmail('author@email.com')
            ->setPassword($this->passwordHasher->hashPassword($userAuthor, 'author'))
            ->setUsername($faker->userName())
            ->setIsActive(1)
            ->setRoles(['ROLE_AUTHOR'])
        ;

        $manager->persist($userAdmin);
        $manager->persist($userAuthor);
        $manager->flush();

        $this->addReference(self::AUTHOR, $userAuthor);

        for ($i = 0; $i < 50; $i++)
        {
            $user = new User;
            $user
                ->setEmail($faker->email())
                ->setPassword($this->passwordHasher->hashPassword($user, '123456789'))
                ->setUsername($faker->userName())
                ->setIsActive(rand(0, 1))
            ;
            $manager->persist($user);
        }

        $manager->flush();
    }
}
