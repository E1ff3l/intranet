<?php

namespace App\Repository;

use App\Entity\Hosting;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Hosting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hosting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hosting[]    findAll()
 * @method Hosting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HostingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hosting::class);
    }

    /**
     * Retourne la liste des hébergements terminant dans moins de 60 jours
     *
     * @param boolean $debug
     * @return void
     */
    public function findATraiter( $debug=false )
    {
        $now =                      new \DateTime();
        $jPlus60 =                  $now->add( new \DateInterval( 'P60D' ) );
        if ( $debug )   dump( $jPlus60 );

        $requete = $this->createQueryBuilder('h')
            ->andWhere('h.fin <= :jPlus60')
            ->setParameter( 'jPlus60', $jPlus60 )
            ->getQuery()
        ;
        if ( $debug )   dump( $requete );

        return $requete->getResult();
    }

    // /**
    //  * @return Hosting[] Returns an array of Hosting objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Hosting
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
