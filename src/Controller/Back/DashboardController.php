<?php

namespace App\Controller\Back;

use App\Entity\Plafond;
use App\Repository\ProjetRepository;
use App\Repository\HostingRepository;
use App\Repository\PlafondRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    
    
    public function dashboard( 
        HostingRepository $repo_hosting, PlafondRepository $repo_plafond, ProjetRepository $repo_projet 
    )
    {

        // ---- Etat des projets NON terminés ------------------------- //
        if ( 1 == 1 ) {
            $liste_projet =         '';

            // ---- Tableau contenant les images associées aux états de projet
            $tab_etat =             array(
                array( 7, 'En attente', '<i class="fa fa-fw fa-phone"></i>' ),
                array( 1, 'Signé', '<i class="fa fa-fw fa-edit"></i>' ),
                array( 2, 'En cours', '<i class="fa fa-fw fa-gears"></i>' ),
                array( 3, 'En test', '<i class="fa fa-fw fa-wrench"></i>' ),
                array( 4, 'Facturé', '<i class="fa fa-fw fa-thumbs-o-up"></i>' ),
                array( 5, 'Payé', '<i class="fa fa-fw fa-bitcoin"></i>' ),
                array( 6, 'Facturé et payé', '<i class="fa fa-fw fa-smile-o"></i>' )
            );
            //dump( $tab_etat );

            foreach ( $tab_etat as $id => $etat ) {
                $projets =          $repo_projet->findBy(
                    [ 
                        "projetEtat" => $etat[ 0 ]
                    ]
                );

                // ---- Projets dans cet état ------------------------- //
                if ( !empty( $projets ) ) {
                    $nb_projet =    count( $projets );
                    $total =        0;

                    foreach ( $projets as $key => $projet ) {
                        $total +=           $projet->getPrix();
                    }
					$total = 		floor( $total );

                    $classe_etat =	'';
					$detail =		( $nb_projet > 1 ) ? $nb_projet . ' projets' : '1 projet';
					$detail .=		' pour ' . $total . '&euro;';

					// ---- En cas de "Facturé & payé" on donne qq infos en plus...
					if ( $etat[ 0 ] == 6 ) {
                        $bestClients = $repo_projet->findBestClients( true );
                        dump( $bestClients );

                        $detail .=	'<br>Dont :';
                        
                        foreach ( $bestClients as $key => $val ) {
                            $pourcent = $val[ 'total' ] * 100 / $total;
                            $detail .=	'<br>- <b>' . $val[ 'societe' ] . '</b> (' . $val[ 'nb' ] . ' projets / ' . floor( $val[ 'total' ] ) . '€ soit <b>' . number_format( $pourcent, 2 ) . ' %</b> )';
                        }
					}
					// ------------------------------------------------ //
                }
                // ---------------------------------------------------- //

                // ---- Aucun projet mais on affiche quand même cette information
                else {
					$classe_etat =	'_vide';
					$detail = 		'-';
                }
                // ---------------------------------------------------- //

                $liste_projet .=    '<a href="/console/projet_liste/' . $etat[ 0 ] . '">';
                $liste_projet .=        '<div class="conteneur_etat">';
                $liste_projet .=            '<div class="etat_picto">' . $etat[ 2 ] . '</div>';
                $liste_projet .=            '<div class="etat_detail">';
                $liste_projet .=                '<span class="titre' .  $classe_etat . '">Projets "' . $etat[ 1 ] . '"</span><br>';
                $liste_projet .=                '<span class="detail' .  $classe_etat . '">' . $detail . '</span>';
                $liste_projet .=            '</div>';
                $liste_projet .=        '	<div style="clear:both;"></div>';
				$liste_projet .=        '</div>';
                $liste_projet .=    '</a>';
            }
        }
        // ------------------------------------------------------------ //

        // ---- CA par exercices -------------------------------------- //
        if ( 1 == 1 ) {
            $now =                  new \DateTime();
            $contenu_ca =           '';
            $ca_precedent =         0;
            $tab_ca =               array();

            for ( $a=($now->format('Y') - 10); $a <= $now->format('Y') ; $a++ ) {
                
                // ---- Définition des plafonds de l'année ------------ //
                $plafond =          $repo_plafond->findOneBy( [ 'annee' => $a ] );
                //dump( $plafond );
                
                // ---- Total des projets "#6 - Facturés & payés" de l'année
                $result =           $repo_projet->findCAByEtatAnnee( 6, $a, 0, false );
                //dd( $result );
                $calcul_ca =        intval( $result[ 0 ][ "total" ] );

                // ---- Pourcentage d'augmentation -------------------- //
                if ($ca_precedent == 0) {
                    $hausse =       '';
                    $ca_precedent =  $calcul_ca;
                } 
                else {
                    $pourcentage =  number_format((($calcul_ca - $ca_precedent) * 100 / $ca_precedent), 2);
                    $hausse =       ($pourcentage >= 0)
                     ? '<span class="label label-success">+' . $pourcentage . '%</span>'
                     : '<span class="label label-danger">' . $pourcentage . '%</span>';
                    
                    $ca_precedent =  $calcul_ca;
                }
                // ---------------------------------------------------- //

                // ---- Pour l'année courante, on détermine les projections sur le CA
                if ( $a == $now->format('Y') ) {
                    $result =       $repo_projet->findProjectionCAByAnnee( $a, false );
                    $projection_ca = $result[ 0 ][ "total" ];

                    if ( $projection_ca > 0 ) {
                        if ($projection_ca >=  $plafond->getTolerance()) {
                            $classe_projection = "danger";
                            $classe_cadre =      "ca-danger";
                        }
                        else if ( $projection_ca >=  $plafond->getLimiteCA() ) {
                            $classe_projection = "warning";
                            $classe_cadre =      "ca-warning";
                        }
                        else {
                            $classe_projection = "success";
                            $classe_cadre =      '';
                        }
                        
                        $calcul_ca .= ' € <span class="label label-' . $classe_projection . '">' . $projection_ca . ' €</span>';
                    }
                }
                else {

                    $calcul_ca .= ' €';

                    if ( !is_null( $plafond ) && $calcul_ca >=  $plafond->getTolerance()) {
                        $classe_projection = "danger";
                        $classe_cadre =      "ca-danger";
                    }
                    else if ( !is_null( $plafond ) && $calcul_ca >=  $plafond->getLimiteCA() ) {
                        $classe_projection = "warning";
                        $classe_cadre =      "ca-warning";
                    }
                    else {
                        $classe_projection = "success";
                        $classe_cadre =      '';
                    }
                }
                // ---------------------------------------------------- //

                $tab_ca[ $a ] = array(
                    'calcul_ca' =>          $calcul_ca,
                    'hausse' =>             $hausse,
                    'classe_cadre' =>       $classe_cadre,
                );
            }

            // ---- On inverse l'ordre des années dans le tableau des résultats
            krsort( $tab_ca );
            
            foreach ($tab_ca as $annee => $vals ) {
                $contenu_ca .=  '<div class="ca ' .  $vals[ "classe_cadre" ] . '">';
                $contenu_ca .=  '   <div class="col-md-3">' . $annee . ' :</div>';
                $contenu_ca .=  '   <div class="col-md-6">' . $vals[ "calcul_ca" ] . '</div>';
                $contenu_ca .=  '   <div class="col-md-3 text-right">' . $vals[ "hausse" ] . '</div>';
                $contenu_ca .=  '</div>';
            }
        }
        // ------------------------------------------------------------ //

        // ---- Déclarations mensuelles ------------------------------- //
        if ( 1 == 1 ) {
            $now =                  new \DateTime();
            $tab_mois = 			array( "", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre" );
            $declaration_mensuelle = '';
            $tab_declaration =      array();
            $cpt =                  0;

            // ---- Déclaration de décembre de l'année précédente ----- //
            if ( 1 == 1 ) {
                $result =           $repo_projet->findCAByEtatAnnee( 6, $now->format( 'Y' )-1, 12, false );
                //dd( $result );
                $calcul_ca =        intval( $result[ 0 ][ "total" ] );

                $tab_declaration[ $now->format( 'Y' )-1 . '-12-01' ] =  $calcul_ca;
            }
            // -------------------------------------------------------- //

            // ---- Déclaration des mois de l'année courante ---------- //
            if ( 1 == 1 ) {
                for ($m=1; $m <= $now->format( 'm' ); $m++) { 
                    $result =           $repo_projet->findCAByEtatAnnee( 6, $now->format( 'Y' ), $m, false );
                    //dump( $result );
                    $calcul_ca =        $result[ 0 ][ "total" ];
                    if ( is_null( $calcul_ca ) ) $calcul_ca = 0;
                    
                    $mois =             ( $m < 10 ) ? '0' . $m : $m;
                    $tab_declaration[ $now->format( 'Y' ) . '-' . $mois . '-01' ] =  $calcul_ca;
                }
            }
            // -------------------------------------------------------- //
            //dump( $tab_declaration );

            // ---- On inverse l'ordre des années dans le tableau des résultats
            krsort( $tab_declaration );
            
            foreach ($tab_declaration as $date => $_ca ) {
                $mk =                   new \DateTime( $date );
                $classe =               ( $cpt % 2 ) ? 'gris' : '';

                $declaration_mensuelle .=  '<div class="ca ' . $classe . '">';
                $declaration_mensuelle .=  '    <div class="col-md-3">' . $tab_mois[ +$mk->format( 'm' ) ] . ' ' . $mk->format( 'Y' ) . ' :</div>';
                $declaration_mensuelle .=  '    <div class="col-md-9 text-right">' . $_ca . ' €</div>';
                $declaration_mensuelle .=  '</div>';

                $cpt++;
            }

        }
        // ------------------------------------------------------------ //

        // ---- Recherche d'hébergements à traiter -------------------- //
        if ( 1 == 1 ) {
            $hostings =                 $repo_hosting->findATraiter();
            //dump( $hostings );
            $now =                      new \DateTime();

            foreach ( $hostings as $key => $hosting ) {
                $diff =                 $hosting->getFin()->diff( $now )->days;
                
                // ---- Différence < 30 jours --> DANGER -------------- //
                if ( $diff <= 30 ) {
                    $lien =             '<a href="' . $this->generateUrl( 'bo_hosting_edition', [ 'id' => $hosting->getId() ] ) . '" class="btn btn-xs btn-danger">Gérer cet hébergement!</a>';
                    $this->addFlash(
                        "danger",
                        "L'hébergement de \"" . $hosting->getSite() . "\" expire le <b>" . $hosting->getFin()->format( 'd/m/Y' ) . "</b> : "
                    );
                }
                // ---------------------------------------------------- //

                // ---- Différence < 60 jours --> WARNING ------------- //
                else if ( $diff <= 60 ) {
                    $lien =             '<a href="' . $this->generateUrl( 'bo_hosting_edition', [ 'id' => $hosting->getId() ] ) . '" class="btn btn-xs btn-warning">Gérer cet hébergement!</a>';
                    $this->addFlash(
                        "warning",
                        "L'hébergement de \"" . $hosting->getSite() . "\" expire le <b>" . $hosting->getFin()->format( 'd/m/Y' ) . "</b> : " . $lien
                    );
                }
                // ---------------------------------------------------- //
            }
        }
        // ------------------------------------------------------------ //

        return $this->render(
            'back/dashboard/dashboard.html.twig', 
            [ 
                'menu_courant' =>   'dashboard',
                'liste_projet' =>   $liste_projet,
                'contenu_ca' =>     $contenu_ca,
                'declaration_mensuelle' => $declaration_mensuelle
            ]
        );
    }
}
