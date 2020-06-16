<?php

namespace App\Repository;

use App\Entity\InfoDiverse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method InfoDiverse|null find($id, $lockMode = null, $lockVersion = null)
 * @method InfoDiverse|null findOneBy(array $criteria, array $orderBy = null)
 * @method InfoDiverse[]    findAll()
 * @method InfoDiverse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfoDiverseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfoDiverse::class);
    }

    // /**
    //  * @return InfoDiverse[] Returns an array of InfoDiverse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InfoDiverse
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
