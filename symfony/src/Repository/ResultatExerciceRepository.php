<?php

namespace App\Repository;

use App\Entity\ResultatExercice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ResultatExercice>
 */
class ResultatExerciceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResultatExercice::class);
    }

    public function findLast3ResultsByExercice(int $id): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exercice = :id')
            ->setParameter('id', $id)
            ->orderBy('a.date', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    public function findLast10ResultsByExercice(int $id): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exercice = :id')
            ->setParameter('id', $id)
            ->orderBy('a.date', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return ResultatExercice[] Returns an array of ResultatExercice objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ResultatExercice
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
