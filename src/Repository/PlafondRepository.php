<?php

namespace App\Repository;

use App\Entity\Plafond;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Plafond|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plafond|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plafond[]    findAll()
 * @method Plafond[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlafondRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plafond::class);
    }

    // /**
    //  * @return Plafond[] Returns an array of Plafond objects
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
    public function findOneBySomeField($value): ?Plafond
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
