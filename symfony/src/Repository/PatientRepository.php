<?php

namespace App\Repository;

use App\Entity\Patient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Patient>
 */
class PatientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Patient::class);
    }

    public function searchPatients(string $query)
    {
        $query = trim(mb_strtolower($query));

        return $this->createQueryBuilder('p')
            ->where('LOWER(p.nom) LIKE LOWER(:query) OR LOWER(p.prenom) LIKE LOWER(:query)')
            ->setParameter('query', "%" . strtolower($query) . "%")
            ->getQuery()
            ->getResult();
    }

    /* public function searchPatients(string $query): array
    {
        $query = strtolower(trim($query));

        $qb = $this->createQueryBuilder('p');
        $qb->where($qb->expr()->orX(
            $qb->expr()->like('LOWER(p.nom)', ':query'),
            $qb->expr()->like('LOWER(p.prenom)', ':query')
        ))
            ->setParameter('query', '%' . $query . '%');

        dump($qb->getQuery()->getSQL()); // pour voir la requête SQL générée
        dump($query); // pour voir ce que contient le paramètre
        return $qb->getQuery()->getResult();
    } */



    //    /**
    //     * @return Patient[] Returns an array of Patient objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Patient
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
