<?php

namespace App\Repository;

use App\Entity\Payment;
use App\Entity\PaymentSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Payment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payment[]    findAll()
 * @method Payment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    public function findPayments(PaymentSearch $search)
    {
        $query = $this->createQueryBuilder('p');

        if ($search->getClub()) {
            $query = $query
                ->andWhere("p.user IN (:users)")
                ->setParameter("users", $search->getClub()->getUsers());
        }
        if ($search->getPaid() == 0) {
            $query = $query
                ->andWhere("p.amount != p.amount_paid");
        }

        return $query
            ->orderBy('p.season', 'desc')
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Payment
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
