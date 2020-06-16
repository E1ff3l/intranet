<?php

namespace App\Repository;

use App\Entity\ProjetAcompte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ProjetAcompte|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjetAcompte|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjetAcompte[]    findAll()
 * @method ProjetAcompte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjetAcompteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjetAcompte::class);
    }

    // /**
    //  * @return ProjetAcompte[] Returns an array of ProjetAcompte objects
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
    public function findOneBySomeField($value): ?ProjetAcompte
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
