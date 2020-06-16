<?php

namespace App\Repository;

use App\Entity\StatRmAnnee;
use App\Repository\StatRmMoisRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method StatRmAnnee|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatRmAnnee|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatRmAnnee[]    findAll()
 * @method StatRmAnnee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatRmAnneeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatRmAnnee::class);
    }



    public function getCAByAnnee( StatRmMoisRepository $repo_stats_mois, $annee, $debug=false ) {
        if ( $debug )   echo "<br><br><br>";
        $resultats =            array();
        $_resultats =           array();

        for ($m=1; $m<=12; $m++) {
            if ( $debug )    echo "----- " . $m . "/" . $annee . " : <br>";

            $moisCourant =      new \DateTime( $annee . '-' . $m . '-01' );
            $tab_result = 		$repo_stats_mois->getStatistiqueByMois($moisCourant, false);

            if ( !empty( $tab_result ) ) {
                $resultats[ "commission" ][ $m ] = $tab_result[ 0 ][ "commission" ];
                $resultats[ "forfait" ][ $m ] = $tab_result[ 0 ][ "forfait" ];
                $resultats[ "ca_total" ][ $m ] = $tab_result[ 0 ][ "ca_total" ];
                $resultats[ "ca_variable" ][ $m ] = $tab_result[ 0 ][ "ca_variable" ];
                $resultats[ "ca_forfait" ][ $m ] = $tab_result[ 0 ][ "ca_forfait" ];
                $resultats[ "mode_livraison" ][ $m ] = $tab_result[ 0 ][ "mode_livraison" ];
                $resultats[ "mode_ae" ][ $m ] = $tab_result[ 0 ][ "mode_ae" ];
                $resultats[ "commande_midi" ][ $m ] = $tab_result[ 0 ][ "commande_midi" ];
                $resultats[ "commande_soir" ][ $m ] = $tab_result[ 0 ][ "commande_soir" ];
                $resultats[ "support_rm" ][ $m ] = $tab_result[ 0 ][ "support_rm" ];
                $resultats[ "support_smart" ][ $m ] = $tab_result[ 0 ][ "support_smart" ];
                $resultats[ "support_vit" ][ $m ] = $tab_result[ 0 ][ "support_vit" ];
                $resultats[ "support_autre" ][ $m ] = $tab_result[ 0 ][ "support_autre" ];
                $resultats[ "client_connecte" ][ $m ] = $tab_result[ 0 ][ "client_connecte" ];
                $resultats[ "client_express" ][ $m ] = $tab_result[ 0 ][ "client_express" ];

            }
            else {
                $resultats[ "commission" ][ $m ] = 0;
                $resultats[ "forfait" ][ $m ] = 0;
                $resultats[ "ca_total" ][ $m ] = 0;
                $resultats[ "ca_variable" ][ $m ] = 0;
                $resultats[ "ca_forfait" ][ $m ] = 0;
                $resultats[ "mode_livraison" ][ $m ] = 0;
                $resultats[ "mode_ae" ][ $m ] = 0;
                $resultats[ "commande_midi" ][ $m ] = 0;
                $resultats[ "commande_soir" ][ $m ] = 0;
                $resultats[ "support_rm" ][ $m ] = 0;
                $resultats[ "support_smart" ][ $m ] = 0;
                $resultats[ "support_vit" ][ $m ] = 0;
                $resultats[ "support_autre" ][ $m ] = 0;
                $resultats[ "client_connecte" ][ $m ] = 0;
                $resultats[ "client_express" ][ $m ] = 0;
            }

            //break;
        }

        //dump($resultats);
        foreach ($resultats as $key => $datas) {
            //dump( $datas );

            foreach ( $datas as $mois => $value ) {
                if ( isset( $_resultats[ $key ] ) ) $_resultats[ $key ] .= ', ' . intval( $value );
                else                                $_resultats[ $key ] = intval( $value );
            } 

            $_resultats[ $key ] = '[ ' . $_resultats[ $key ]  . ' ]';
        }
        //dump($_resultats);
        
        return $_resultats;
    }

    // /**
    //  * @return StatRmAnnee[] Returns an array of StatRmAnnee objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StatRmAnnee
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
