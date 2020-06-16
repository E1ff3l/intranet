<?php

namespace App\Service;

use App\Entity\StatRmJour;
use App\Entity\StatRmMois;
use App\Entity\StatRmAnnee;
use App\Repository\StatRmJourRepository;
use App\Repository\StatRmMoisRepository;
use App\Repository\StatRmAnneeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class StatistiqueService extends AbstractController {

    
	/**
	 * Retourne les statistiques demandées
	 *
	 * @param [type] $today
	 * @param [type] $intervalle
	 * @param integer $debug
	 * @return void
	 */
	public function getStatistiqueWithCurl( $debut, $intervalle, $debug=0 ) {
		if ( $debug == 1 ) {
            dump( $debut );
            dump( $intervalle );
		}

		$page =		"https://restomalin.com/console/script_restomalin.php";
		$page .=	"?cle=ADMINQz5kW9kWfranck2isdj45aDH6jd54reoz2dlzkdhryzfsvdhfseg45eekdk";
		$page .=	"&m=1000";
		$page .=	"&d=" . $debut->getTimestamp();
		$page .=	"&f=" . $debut->add( new \DateInterval( $intervalle ) )->getTimestamp();		// new \DateInterval('P1D')
		//$page .=	"&f=" . $debut->add(new \DateInterval('P1D'))->getTimestamp();
		$page .=	"&dbg=" . $debug;
		if ( $debug == 1 )	echo "--- Page : " . $page . "<br>";
		
		$curl = 				curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_URL, $page);
		$result = 				curl_exec($curl);
		curl_close($curl);
		//echo $result . "<br><br>";

		return  $result;
	}


	public function majStatistiques( 
		ObjectManager $manager, 
		StatRmAnneeRepository $repo_stats_annee, StatRmMoisRepository $repo_stats_mois, StatRmJourRepository $repo_stats_jour, 
		$debug=false 
	) {
		$today =        				new \DateTime();

		// ---- Traitement sur les statistiques journalières -------------- //
		if ( 1 == 1 ) {
			if ($debug)	echo "<br><br><br>";

			// ---- On détermine l'intervalle de temps à traiter ---------- //
			if ( 1 == 1 ) {
				$requete = 				$repo_stats_jour->createQueryBuilder('s')
					->select('s.jour')
					->orderBy('s.jour', 'DESC')
					->setMaxResults(1)
					->getQuery()
				;
				//if ($debug)	dump($requete);
				
				$dernierJourTraite =	new \DateTime( $requete->getSingleScalarResult() );
				$dernierJourTraite->add( new \DateInterval('P1D') );
				if ($debug)	echo "dernierJourTraite : " . $dernierJourTraite->format( 'd/m/Y' ) . "<br>";
				if ($debug)	echo "today : " . $today->format( 'd/m/Y' ) . "<br>";
	
				$intervalle = 			date_diff($dernierJourTraite, $today);
				//dump( $intervalle);

				$intervalle = 			$intervalle->format('P%aD');
				//dd( $intervalle );
			}
			// ------------------------------------------------------------ //
			
			// ---- Récupération des statistiques à traiter --------------- //
			$result = 					$this->getStatistiqueWithCurl( $today->sub( new \DateInterval( $intervalle ) ), $intervalle, 0 );
			$tab_result =   			\unserialize($result);
			//dd( $tab_result );
	
			// ---- Enregistrement des statistiques reçues ---------------- //
			if ( !empty( $tab_result ) ) {
				foreach ( $tab_result as $jour => $datas ) {
					if ($datas[ "com" ] > 0) {
						if ($debug)	echo $jour . " : Qq à traiter...<br>";
						dump($datas);
	
						$stat_jour = 	new StatRmJour();
						$stat_jour->setJour(new \DateTime($jour))
							->setCommission(number_format(floatval($datas[ 'com' ]), 2, '.', ''))
							->setForfait(number_format(floatval($datas[ 'forfait' ]), 2, '.', ''))
							->setCaTotal(number_format(floatval($datas[ 'ca' ][ 'ca_total' ]), 2, '.', ''))
							->setCaVariable(number_format(floatval($datas[ 'ca' ][ 'ca_variable' ]), 2, '.', ''))
							->setCaForfait(number_format(floatval($datas[ 'ca' ][ 'ca_forfait' ]), 2, '.', ''))
							->setModeLivraison(number_format(floatval($datas[ 'mode' ][ 'livraison' ]), 2, '.', ''))
							->setModeAe(number_format(floatval($datas[ 'mode' ][ 'ae' ]), 2, '.', ''))
							->setCommandeMidi(number_format(floatval($datas[ 'commande' ][ 'midi' ]), 2, '.', ''))
							->setCommandeSoir(number_format(floatval($datas[ 'commande' ][ 'soir' ]), 2, '.', ''))
							->setSupportRm(number_format(floatval($datas[ 'support' ][ 'rm' ]), 2, '.', ''))
							->setSupportSmart(number_format(floatval($datas[ 'support' ][ 'smart' ]), 2, '.', ''))
							->setSupportVit(number_format(floatval($datas[ 'support' ][ 'vit' ]), 2, '.', ''))
							->setSupportAutre(number_format(floatval($datas[ 'support' ][ 'autre' ]), 2, '.', ''))
							->setClientConnecte(number_format(floatval($datas[ 'client' ][ 'connecte' ]), 2, '.', ''))
							->setClientExpress(number_format(floatval($datas[ 'client' ][ 'express' ]), 2, '.', ''));
	
						$manager->persist($stat_jour);
						//break;
					}
	
				}
			}
			// ------------------------------------------------------------ //

		}
		// ---------------------------------------------------------------- //

		// ---- Traitement sur les statistiques mensuelles ---------------- //
		if ( 1 == 1 ) {
			if ($debug)	echo "<br><br><br>";
			//$today =        				new \DateTime( '2016-03-01' );

			// ---- On détermine le dernier mois traité ------------------- //
			if ( 1 == 1 ) {
				$requete = 				$repo_stats_mois->createQueryBuilder('s')
					->select('s.jour')
					->orderBy('s.jour', 'DESC')
					->setMaxResults(1)
					->getQuery()
				;
				if ($debug)	dump($requete);
				
				$dernierMoisTraite = 	( !empty( $requete->getResult() ) )
					? new \DateTime( $requete->getSingleScalarResult() )
					: new \DateTime('2010-03-01');
				if ($debug)	echo "dernierMoisTraite : " . $dernierMoisTraite->format( 'd/m/Y' ) . "<br>";
			}
			// ------------------------------------------------------------ //
			
			// ---- Récupération des statistiques à traiter --------------- //
			if ( 1 == 1 ) {
				$plus1Mois = 			new \DateInterval( 'P1M' );
				$moisCourant = 			new \DateTime( $today->format( 'Y-m-01' ) );
				if ($debug)	echo "moisCourant : " . $moisCourant->format( 'd/m/Y' ) . "<br>";

				for ( $m=$dernierMoisTraite->add( $plus1Mois ); $m<$moisCourant; $m=$m->add( $plus1Mois )) {
					//dump( $m->format( 'd/m/Y' ) );
					$tab_result = 		$repo_stats_jour->getStatistiqueByMois( $m, $debug );

					// ---- Enregistrement des statistiques reçues -------- //
					if (!empty($tab_result)) {
						foreach ($tab_result as $jour => $datas) {
							if ( $datas[ "ca_total" ] > 0 ) {
								if ($debug)	echo $m->format( 'd/m/Y' ) . " : Qq à traiter...<br>";
								//dump( $datas );
			
								$stat_mois = 	new StatRmMois();
								$stat_mois->setJour( new \DateTime( $m->format( 'Y-m-d' ) ) )
									->setCommission(number_format(floatval($datas[ 'commission' ]), 2, '.', ''))
									->setForfait(number_format(floatval($datas[ 'forfait' ]), 2, '.', ''))
									->setCaTotal(number_format(floatval($datas[ 'ca_total' ]), 2, '.', ''))
									->setCaVariable(number_format(floatval($datas[ 'ca_variable' ]), 2, '.', ''))
									->setCaForfait(number_format(floatval($datas[ 'ca_forfait' ]), 2, '.', ''))
									->setModeLivraison(number_format(floatval($datas[ 'mode_livraison' ]), 2, '.', ''))
									->setModeAe(number_format(floatval($datas[ 'mode_ae' ]), 2, '.', ''))
									->setCommandeMidi(number_format(floatval($datas[ 'commande_midi' ]), 2, '.', ''))
									->setCommandeSoir(number_format(floatval($datas[ 'commande_soir' ]), 2, '.', ''))
									->setSupportRm(number_format(floatval($datas[ 'support_rm' ]), 2, '.', ''))
									->setSupportSmart(number_format(floatval($datas[ 'support_smart' ]), 2, '.', ''))
									->setSupportVit(number_format(floatval($datas[ 'support_vit' ]), 2, '.', ''))
									->setSupportAutre(number_format(floatval($datas[ 'support_autre' ]), 2, '.', ''))
									->setClientConnecte(number_format(floatval($datas[ 'client_connecte' ]), 2, '.', ''))
									->setClientExpress(number_format(floatval($datas[ 'client_express' ]), 2, '.', ''));

								$manager->persist($stat_mois);
								//break;
							}
						}
					}
					// ---------------------------------------------------- //

				}
			}
            // ------------------------------------------------------------ //

		}
		// ---------------------------------------------------------------- //

		// ---- Traitement sur les statistiques annuelles ----------------- //
		if ( 1 == 1 ) {

            // ---- On détermine la dernière année traitée ---------------- //
            if (1 == 1) {
                $requete = 				$repo_stats_annee->createQueryBuilder('s')
                    ->select('s.annee')
                    ->orderBy('s.annee', 'DESC')
                    ->setMaxResults(1)
                    ->getQuery()
                ;
                if ($debug) dump($requete);
                
                $derniereAnneeTraitee =	(!empty($requete->getResult()))
                    ? $requete->getSingleScalarResult()
                    : 2010;
                if ($debug) dump($derniereAnneeTraitee);
            }
            // ------------------------------------------------------------ //
            
            // ---- Récupération des statistiques à traiter --------------- //
            if (1 == 1) {
				if ($debug)	echo "<br><br><br>";
				if ($debug)	echo "--- Dernière année traitée : " . $derniereAnneeTraitee . "<br>";

				$anneeCourante = 		$today->format('Y');
				if ($debug)	echo "--- Année courante : " . $anneeCourante . "<br>";

                for ( $a=($derniereAnneeTraitee+1); $a<$anneeCourante; $a++ ) {
                    //dump( $m->format( 'd/m/Y' ) );
                    $tab_result = 		$repo_stats_mois->getStatistiqueByAnnee($a, $debug);

                    // ---- Enregistrement des statistiques reçues -------- //
                    if (!empty($tab_result)) {
                        foreach ($tab_result as $jour => $datas) {
                            if ($datas[ "ca_total" ] > 0) {
                                if ($debug)	echo $a . " : Qq à traiter...<br>";
                                //dump($datas);
            
                                $stat_annee = 	new StatRmAnnee();
                                $stat_annee->setAnnee( $a )
                                    ->setCommission(number_format(floatval($datas[ 'commission' ]), 2, '.', ''))
                                    ->setForfait(number_format(floatval($datas[ 'forfait' ]), 2, '.', ''))
                                    ->setCaTotal(number_format(floatval($datas[ 'ca_total' ]), 2, '.', ''))
                                    ->setCaVariable(number_format(floatval($datas[ 'ca_variable' ]), 2, '.', ''))
                                    ->setCaForfait(number_format(floatval($datas[ 'ca_forfait' ]), 2, '.', ''))
                                    ->setModeLivraison(number_format(floatval($datas[ 'mode_livraison' ]), 2, '.', ''))
                                    ->setModeAe(number_format(floatval($datas[ 'mode_ae' ]), 2, '.', ''))
                                    ->setCommandeMidi(number_format(floatval($datas[ 'commande_midi' ]), 2, '.', ''))
                                    ->setCommandeSoir(number_format(floatval($datas[ 'commande_soir' ]), 2, '.', ''))
                                    ->setSupportRm(number_format(floatval($datas[ 'support_rm' ]), 2, '.', ''))
                                    ->setSupportSmart(number_format(floatval($datas[ 'support_smart' ]), 2, '.', ''))
                                    ->setSupportVit(number_format(floatval($datas[ 'support_vit' ]), 2, '.', ''))
                                    ->setSupportAutre(number_format(floatval($datas[ 'support_autre' ]), 2, '.', ''))
                                    ->setClientConnecte(number_format(floatval($datas[ 'client_connecte' ]), 2, '.', ''))
                                    ->setClientExpress(number_format(floatval($datas[ 'client_express' ]), 2, '.', ''));

                                $manager->persist($stat_annee);
                                //break;
                            }
                        }
                    }
                    // ---------------------------------------------------- //
                }
            }
			// ------------------------------------------------------------ //

		}
		// ---------------------------------------------------------------- //

		$manager->flush();

	}


}