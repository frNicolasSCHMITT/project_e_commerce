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
        //     $article->setDescription('Test article description :           

        //         Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin accumsan lobortis turpis et molestie. Donec a tellus nisl. Aenean luctus lacus at ipsum consectetur placerat. Donec ac feugiat felis. Cras ut sagittis leo. Mauris quis pharetra lorem. Sed mollis sed metus ut porttitor. Quisque vel placerat lectus, sit amet tempus justo. Morbi nec massa nec ipsum elementum mollis quis ut mauris. Maecenas eget bibendum tortor, eu blandit dui. Vestibulum malesuada, massa a mattis pulvinar, mi sapien tristique risus, eget dignissim est metus non massa. Vestibulum ac consequat ex. In leo ex, ultrices in est non, volutpat aliquam massa. Vestibulum eget venenatis tellus, vitae dignissim nunc. Sed lorem neque, viverra fermentum mauris sed, dignissim viverra dolor. Phasellus tempor non ipsum at bibendum.

        //         Nulla eget nibh molestie, dapibus nibh fermentum, semper tellus. Fusce interdum volutpat turpis, vel hendrerit mauris molestie sed. Sed lobortis, risus vel ullamcorper tempor, magna quam varius lorem, et vestibulum massa turpis eu sapien. Sed eu ante at nunc aliquam cursus. Sed malesuada tincidunt risus, eget venenatis urna ornare quis. Vivamus convallis ultricies turpis a eleifend. Nulla ut lorem at libero facilisis feugiat. Curabitur cursus quam ut leo luctus, sed scelerisque libero volutpat. Aenean tincidunt quis ipsum fermentum elementum. Quisque ac finibus tellus. Maecenas quis risus ut mauris ornare scelerisque. Vestibulum mollis odio ipsum, eget vestibulum purus tempor ac. Nulla nec velit id nisl semper dapibus.

        //         Fusce id nulla cursus, convallis tellus at, maximus dui. Proin euismod, est nec euismod ullamcorper, dui nisi rhoncus mi, nec consectetur est ipsum eget sapien. Donec eu enim quis sapien scelerisque dignissim. Donec fringilla volutpat tortor vel luctus. Ut at mauris rhoncus, commodo ex a, maximus lacus. Suspendisse aliquam tempor dui id gravida. Suspendisse sollicitudin lorem elit. Mauris dictum ipsum eu enim pretium, id luctus purus vulputate. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.

        //         Integer sed congue quam. Duis mattis volutpat mi eget semper. Quisque fermentum neque lacus, in malesuada dui dapibus ut. Ut lobortis turpis et erat eleifend, ac dignissim arcu vehicula. Duis tincidunt leo quam. Aenean ac elit consectetur ligula malesuada mollis. Duis venenatis ligula id lectus vestibulum, nec rutrum velit gravida.

        //         Integer a interdum ante, a maximus arcu. Aliquam maximus mi ex. Proin euismod elit lectus, id faucibus lorem aliquet eget. Nam condimentum sapien sit amet tortor congue sagittis non at eros. Suspendisse sit amet ultrices risus, quis egestas sapien. Aliquam vitae nisi nisi. Curabitur accumsan nisi sit amet magna fringilla, eget pulvinar arcu gravida. Mauris blandit ante at nisl auctor commodo. Nulla vel lorem felis. ');

        //     $article->setPrice(mt_rand(100, 10000));
        //     $article->setAvailable(true);
        //     $manager->persist($article);
        // }

        // $manager->flush();
    }
}
