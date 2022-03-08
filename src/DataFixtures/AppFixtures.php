<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // for ($i = 0; $i < 50; $i++) {
        //     $article = new Article();
        //     $article->setName('Article '.$i);
        //     $article->setPicture('https://cdn.pixabay.com/photo/2015/12/23/01/14/edit-1105049_960_720.png');
        //     $article->setDescription('Test article description');
        //     $article->setPrice(mt_rand(100, 10000));
        //     $article->setAvailable(true);
        //     $manager->persist($article);
        // }

        $manager->flush();
    }
}
