<?php

namespace App\Repository;

use App\Entity\BankTransactions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BankTransactions|null find($id, $lockMode = null, $lockVersion = null)
 * @method BankTransactions|null findOneBy(array $criteria, array $orderBy = null)
 * @method BankTransactions[]    findAll()
 * @method BankTransactions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BankTransactionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BankTransactions::class);
    }

    // /**
    //  * @return BankTransactions[] Returns an array of BankTransactions objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BankTransactions
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
