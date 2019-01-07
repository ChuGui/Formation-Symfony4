<?php
namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class Stats {

    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function getStats(){
        $users = $this->getUsersCount();
        $annonces = $this->getAnnoncesCount();
        $bookings = $this->getBookingsCount();
        $comments = $this->getCommentsCount();

        return compact('users', 'annonces', 'bookings', 'comments');
    }

    public function getAnnoncesStats($order) {
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
          FROM App\Entity\Comment c
          JOIN c.annonce a
          JOIN a.author u
          GROUP BY a
          ORDER BY note ' . $order
        )->setMaxResults(5)
            ->getResult();
    }

    public function getUsersCount(){
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getAnnoncesCount(){
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Annonce a')->getSingleScalarResult();
    }

    public function getCommentsCount(){
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();
    }

    public function getBookingsCount(){
        return $this->manager->createQuery('SELECT COUNT(b) FROM App\Entity\Booking b')->getSingleScalarResult();

    }



}