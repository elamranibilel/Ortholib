<?php

namespace App\Repository;

use App\Entity\Exercice;
use App\Entity\Jeu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Jeu>
 */
class ExerciceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exercice::class);
    }

    public function exerciceTypeVocabulaire() {
        return $this->createQueryBuilder('a')
        ->where('a.type = :type')
        ->setParameter('type', 'Vocabulaire')
        ->getQuery()
        ->getResult();
    }

    public function exerciceTypeOrthographe() {
        return $this->createQueryBuilder('a')
        ->where('a.type = :type')
        ->setParameter('type', 'Orthographe')
        ->getQuery()
        ->getResult();
    }

    public function exerciceTypeMemoire() {
        return $this->createQueryBuilder('a')
        ->where('a.type = :type')
        ->setParameter('type', 'Memoire')
        ->getQuery()
        ->getResult();
    }
    //    /**
    //     * @return Jeu[] Returns an array of Jeu objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('j.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Jeu
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
