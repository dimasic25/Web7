<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\News;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $newsCount = 10;

        for ($i = 1; $i < $newsCount; $i++) {
            $news = new News();
            $news->setAnnotation("Главная тема новости $i");
            $news->setTitle("Новость №$i");
            $news->setDescription("Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...");

            $commentCount = rand(2, 8);
            for ($j = 1; $j < $commentCount; $j++) {
                $comment = new Comment();
                $comment->setBody("Актуальная новость $i");
                $manager->persist($comment);
                $news->addComment($comment);
            }

            $manager->persist($news);
        }

        $manager->flush();
    }
}
