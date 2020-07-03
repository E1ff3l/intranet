<?php

namespace App\Repository;

use App\Entity\StatRmJour;
use App\Service\StatistiqueService;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method StatRmJour|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatRmJour|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatRmJour[]    findAll()
 * @method StatRmJour[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatRmJourRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
		parent::__construct($registry, StatRmJour::class);
	}
	
	/**
	 * Retourne les chiffres du mois courant
	 *
	 * @param boolean $debug
	 * @return void
	 */
    public function getCAMoisCourant( $debug=false ) {
		$today =                    new \DateTime();
		$debut =                    new \DateTime( $today->format( 'Y-m' ) . '-01' );

		if ( $debug ) {
			echo"<br><br><br>";
			echo "--- today : " . $today->format('d/m/Y') . "<br>";
			echo "--- debut : " . $debut->format('d/m/Y') . "<br>";
		}

		// ---- Données recuillies depuis le début du mois ---------------- //
		if ( 1 == 1 ) {
			$requete = 				$this->createQueryBuilder('s')
				->select('sum(s.ca_total) as ca_total, sum(s.commission) as commission, sum(s.forfait) as forfait')
				->andWhere('s.jour >= :debut AND s.jour < :today')
				->setParameter('debut', $debut)
				->setParameter('today', $today)
				->getQuery()
			;
			if ($debug)	dump( $requete );
			
			$datas = 				$requete->getResult();
			if ( $debug )	dump( $datas );
			$ca_total = 			$datas[ 0 ][ 'ca_total' ];
			$commission = 			$datas[ 0 ][ 'commission' ];
			$forfait = 				$datas[ 0 ][ 'forfait' ];
		}
        // ---------------------------------------------------------------- //

		// ---- Données d'aujourd'hui ------------------------------------- //
		if ( 1 == 1 ) {
			$statistique = 			new StatistiqueService();
			$result = 				$statistique->getStatistiqueWithCurl( $today->sub( new \DateInterval( 'P1D' ) ), 'P1D', 0 );
			$tab_result =   \unserialize($result);
			if ( 1 == 1 || $debug)	dump( $tab_result );
			
			foreach ($tab_result as $jour => $value ) {
				$ca_total +=		$value[ 'ca' ][ 'ca_total' ];
				$commission +=		$value[ 'com' ];
				$forfait +=			$value[ 'forfait' ];
			}
		}
		// ---------------------------------------------------------------- //
		
		// ---- Les 5% ---------------------------------------------------- //
		if ( 1 == 1 ) {
			$ma_comm = 				( $commission + $forfait ) * 0.05;
			$prevision_ma_com = 	$ma_comm * $today->format('t') / $today->format('d');
		}

		// ---- Prévisions sur le mois ------------------------------------ //
		if ( 1 == 1 ) {
			$prevision = 			$ca_total * $today->format( 't' ) / $today->format( 'd' );
			$prevision_com = 		( $commission + $forfait ) * $today->format( 't' ) / $today->format( 'd' );
		}
		// ---------------------------------------------------------------- //

		// ---- Retourne une chaine du genre [ 250, 5000 ]" ([ "CA réalisé", "Prévision sur le mois" ])
		$retour[ 'ca' ] = 					"[ " . number_format( $ca_total, 2, '.', '' ) . ", " . number_format( ( $prevision - $ca_total ), 2, '.', '' ) . ", 0, 0, 0 ]";
		$retour[ 'commission' ] = 			"[ 0, 0, " . number_format( $forfait, 2, '.', '' ) . ", " . number_format( $commission, 2, '.', '' ) . ", " . number_format( ( $prevision_com - $commission - $forfait ), 2, '.', '' ) . " ]";
		$retour[ 'comm_kiki' ] = 			"[ 0, 0, 0, 0, 0, " . number_format( $ma_comm, 2, '.', '' ) . ", " . number_format( ( $prevision_ma_com - $ma_comm ), 2, '.', '' ) . " ]";

		/*$retour[ 'ca' ] = 					"[ 100, 200, 0, 0, 0 ]";
        $retour[ 'commission' ] = 			"[ 0, 0, 200, 500, 1000 ]";
        $retour[ 'comm_kiki' ] = 			"[ 0, 0, 0, 0, 0, 8, 12 ]";*/

		//dump( $retour );
        return $retour;
    }

	/**
	 * Retourne la liste des statistiques du mois donné
	 *
	 * @param [type] $moisCourant
	 * @param boolean $debug
	 * @return void
	 */
    public function getStatistiqueByMois( $moisCourant, $debug=false ) {
		$moisProchain = 		new \DateTime( $moisCourant->format( 'Y-m-d' ) );
		$moisProchain = 		$moisProchain->add( new \DateInterval( 'P1M' ) );
		//dump( "--->" . $moisCourant->format( 'd/m/Y' ) . ' / ' . $moisProchain->format( 'd/m/Y' ) );

        $requete = 				$this->createQueryBuilder('s')
			->select('
				sum(s.commission) as commission, sum(s.forfait) as forfait, sum(s.ca_total) as ca_total, sum(s.ca_variable) as ca_variable, sum(s.ca_forfait) as ca_forfait,
				sum(s.mode_livraison) as mode_livraison, sum(s.mode_ae) as mode_ae, 
				sum(s.commande_midi) as commande_midi, sum(s.commande_soir) as commande_soir, 
				sum(s.support_rm) as support_rm, sum(s.support_smart) as support_smart, sum(s.support_vit) as support_vit, sum(s.support_autre) as support_autre, 
				sum(s.client_connecte) as client_connecte, sum(s.client_express) as client_express
			')
			->andWhere('s.jour >= :debut AND s.jour < :fin')
            ->setParameter( 'debut', $moisCourant )
            ->setParameter( 'fin', $moisProchain )
            ->getQuery()
        ;
        if ($debug) dump($requete);
        
        $datas = 				$requete->getResult();
		if ($debug) dump( $datas );
		
		return $datas;
    }

    // /**
    //  * @return StatRmJour[] Returns an array of StatRmJour objects
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
    public function findOneBySomeField($value): ?StatRmJour
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
