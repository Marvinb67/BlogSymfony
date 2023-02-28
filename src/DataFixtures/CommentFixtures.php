<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Symfony\Component\String\Slugger\SluggerInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct
    (
        private UserRepository $userRepository,
        private ArticleRepository $articleRepository,
        private SluggerInterface $sluggerInterface
    )
    {}

    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');

        $users = $this->userRepository->findAll();
        $articles = $this->articleRepository->findAll();

        $articleLength = count($articles) - 1;

        for($i = 0; $i < 300; $i++)
        {
            $comment = new Comment();

            $randomUser = array_rand($users);
            $user = $users[$randomUser];

            $randArticle = array_rand($articles);
            $article = $articles[$randArticle];

            $comment
                ->setArticle($article)
                ->setContent($faker->sentences(3, true))
                ->setIsActive(rand(0,1))
            ;

            if($user->getRoles() !== ['ROLE_ADMIN'] && $user->getRoles() !== ['ROLE_AUTHOR']) $comment->setAuthor($user);

            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            ArticleFixtures::class,
        );
    }
}
