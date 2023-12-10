<?php

namespace App\Repository;

use App\Entity\Asking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Asking>
 *
 * @method Asking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Asking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Asking[]    findAll()
 * @method Asking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AskingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Asking::class);
    }

//    /**
//     * @return Asking[] Returns an array of Asking objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Asking
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
