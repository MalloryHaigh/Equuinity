<?php

namespace App\Repository;

use App\Entity\ForumReplies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ForumReplies|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumReplies|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumReplies[]    findAll()
 * @method ForumReplies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumRepliesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumReplies::class);
    }

    // /**
    //  * @return ForumReplies[] Returns an array of ForumReplies objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ForumReplies
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
