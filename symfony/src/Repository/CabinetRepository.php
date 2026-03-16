<?php

namespace App\Repository;

use App\Entity\Cabinet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cabinet>
 */
class CabinetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cabinet::class);
    }

    public function findNomASC()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getOrthophonistesByCabinet(Cabinet $cabinet)
    {
        return $this->createQueryBuilder('c')
            ->where('c.cabinet = :cabinet')
            ->setParameter('cabinet', $cabinet)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Cabinet[] Returns an array of Cabinet objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Cabinet
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
