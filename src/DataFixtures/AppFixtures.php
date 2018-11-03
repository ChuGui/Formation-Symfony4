<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        for ($i = 1; $i <= 30; $i++) {
            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000, 350);
            $introduction = $faker->paragraph(2);
            $content = "<p>" . join("</p><p>", $faker->paragraphs(5)) . "</p>";

            $annonce = new Annonce();


            $annonce->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(2, 6));

            for ($j = 1; $j <= mt_rand(2, 5); $j++) {
                $image = new Image();
                $image->setCaption($faker->sentence())
                    ->setUrl($faker->imageUrl())
                    ->setAnnonce($annonce);
                $manager->persist($image);
            }

            $manager->persist($annonce);
        }

        $manager->flush();
    }
}
