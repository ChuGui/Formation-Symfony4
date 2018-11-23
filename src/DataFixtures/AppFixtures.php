<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        $users = [];
        $genres = ['male', 'female'];

        //Nous gérons les utilisateurs
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setHash($hash)
            ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }

        // Nous gerons les annonces
        for ($i = 1; $i <= 30; $i++) {
            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000, 350);
            $introduction = $faker->paragraph(2);
            $content = "<p>" . join("</p><p>", $faker->paragraphs(5)) . "</p>";

            $annonce = new Annonce();

            $user = $users[mt_rand(0, count($users)-1)];

            $annonce->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(2, 6))
            ->setAuthor($user);

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
