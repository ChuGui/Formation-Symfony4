<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');
        $slugify = new Slugify();

        for($i = 1; $i <= 30; $i++)
        {
            $title = $faker->sentence();
            $slug = $slugify->slugify($title);
            $coverImage = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            $content = "<p>" . join("</p><p>" , $faker->paragraphs(5)) . "</p>" ;

            $annonce = new Annonce();


            $annonce->setTitle($title)
                ->setSlug($slug)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40,200))
                ->setRooms(mt_rand(2,6));
            $manager->persist($annonce);
        }

        $manager->flush();
    }
}
