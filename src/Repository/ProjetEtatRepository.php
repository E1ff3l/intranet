<?php

namespace App\Repository;

use App\Entity\ProjetEtat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ProjetEtat|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjetEtat|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjetEtat[]    findAll()
 * @method ProjetEtat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjetEtatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjetEtat::class);
    }

    // /**
    //  * @return ProjetEtat[] Returns an array of ProjetEtat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProjetEtat
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
