<?php

namespace App\Repository;

use App\Entity\ProjetFichier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ProjetFichier|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjetFichier|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjetFichier[]    findAll()
 * @method ProjetFichier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjetFichierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjetFichier::class);
    }

    // /**
    //  * @return ProjetFichier[] Returns an array of ProjetFichier objects
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
    public function findOneBySomeField($value): ?ProjetFichier
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
