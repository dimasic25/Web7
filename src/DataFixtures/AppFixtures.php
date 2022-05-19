<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\News;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@mail.ru');
        $admin->setName('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, 'admin'));
        $manager->persist($admin);

        $user = new User();
        $user->setEmail('user@mail.ru');
        $user->setName('Дмитрий');
        $user->setPassword($this->userPasswordHasher->hashPassword($user, 'password'));
        $manager->persist($user);

        $newsCount = 10;

        for ($i = 1; $i < $newsCount; $i++) {
            $news = new News();
            $news->setAnnotation("Главная тема новости $i");
            $news->setTitle("Новость №$i");
            $news->setDescription("Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...");
            $news->setOwner($admin);

            $commentCount = rand(2, 8);
            for ($j = 1; $j < $commentCount; $j++) {
                $comment = new Comment();
                $comment->setOwner($user);
                $comment->setBody("Актуальная новость $i");
                $manager->persist($comment);
                $news->addComment($comment);
            }

            $manager->persist($news);
        }

        $manager->flush();
    }
}
