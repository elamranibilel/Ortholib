<?php

namespace App\Repository;

use App\Entity\Orthophoniste;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Orthophoniste>
 */
class OrthophonisteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orthophoniste::class);
    }

    public function SpecialisationSurdite()
    {
        return $this->createQueryBuilder('a')
            ->where('a.specialisation = :specialisation')
            ->setParameter('specialisation', 'Troubles auditifs et surdité')
            ->getQuery()
            ->getResult();
    }

    public function SpecialisationCommunication()
    {
        return $this->createQueryBuilder('a')
            ->where('a.specialisation = :specialisation')
            ->setParameter('specialisation', 'Troubles cognitifs et communication alternative')
            ->getQuery()
            ->getResult();
    }

    public function SpecialisationEcriture()
    {
        return $this->createQueryBuilder('a')
            ->where('a.specialisation = :specialisation')
            ->setParameter('specialisation', 'Troubles du langage écrit')
            ->getQuery()
            ->getResult();
    }

    public function searchOrthophonistes(string $query)
    {
        return $this->createQueryBuilder('o')
            ->where('o.nom LIKE :query OR o.prenom LIKE :query')
            ->setParameter('query', "%" . $query . "%")
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Orthophoniste[] Returns an array of Orthophoniste objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Orthophoniste
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
