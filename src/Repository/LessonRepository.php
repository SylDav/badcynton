<?php

namespace App\Repository;

use App\Entity\Lesson;
use App\Entity\User;
use App\Entity\Club;
use App\Entity\LessonSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Lesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lesson[]    findAll()
 * @method Lesson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LessonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lesson::class);
    }

    public function findAllByUser(Club $club)
    {
        return $this->createQueryBuilder('l')
            ->where('l.club = :club')
            ->setParameter('club', $club)
            ->orderBy('l.date', 'desc')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllFor5MonthsByUser($club)
    {
        $today = new \DateTime;
        $date = clone $today;
        // On prépare nos extrêmes
        $today->modify('+2 months'); // On ajoute 2 mois
        $date->modify('-2 months');  // On retire 2 mois
        $from = new \DateTime($date->modify('-2 months')->format('Y-m-') . '01');    // On met la date du jour au 1er du mois
        $to = new \DateTime($today->format('Y-m-') . date('t', $date->format('m'))); // On met la date du jour au dernier jour du mois

        return $this->createQueryBuilder('l')
            ->where('l.club = :club')
            ->andWhere('l.date > :from')
            ->andWhere('l.date < :to')
            ->setParameter('club', $club)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllNextByUser($club)
    {
        $today = new \DateTime;
        return $this->createQueryBuilder('l')
            ->where('l.club = :club')
            ->andWhere('l.date > :date')
            ->setParameter('club', $club)
            ->setParameter('date', new \DateTime)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findLessons(LessonSearch $search)
    {
        $query = $this->createQueryBuilder('l');

        if ($search->getClubs()->count() > 0) {
            $query = $query
                ->andWhere("l.club IN (:clubs)")
                ->setParameter("clubs", $search->getClubs());
        }
        if ($search->getThemes()->count() > 0) {
            $query = $query
                ->andWhere("l.theme IN (:themes)")
                ->setParameter("themes", $search->getThemes());
        }

        return $query
            ->orderBy('l.date', 'desc')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Lesson[] Returns an array of Lesson objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Lesson
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
