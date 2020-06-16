<?php

namespace App\Controller\Back;

use App\Entity\StatRmJour;
use App\Service\StatistiqueService;
use App\Repository\StatRmJourRepository;
use App\Repository\StatRmMoisRepository;
use App\Repository\StatRmAnneeRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RestomalinController extends AbstractController
{

    /**
     * Affichage de différentes statistiques sur Restomalin
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @param StatistiqueService $statistique
     * @param StatRmAnneeRepository $repo_stats_annee
     * @param StatRmMoisRepository $repo_stats_mois
     * @param StatRmJourRepository $repo_stats_jour
     * @return void
     */
    public function index( 
        Request $request, ObjectManager $manager, 
        StatistiqueService $statistique,
        StatRmAnneeRepository $repo_stats_annee, StatRmMoisRepository $repo_stats_mois, StatRmJourRepository $repo_stats_jour )
    {

        // ---- MAJ éventuelle des statistiques --------------------------- //
        $statistique->majStatistiques( 
            $manager, 
            $repo_stats_annee, $repo_stats_mois, $repo_stats_jour, 
            false 
        );
        // ---------------------------------------------------------------- //

        // ---- Définition de l'année à visualiser ------------------------ //
        $_annee =                   intval( $request->get('annee') );
        $anneeCourante =            ( $_annee >= 2010 ) ? $_annee : date( 'Y' );
        //dd( "fin");
        
        return $this->render(
            'back/restomalin/index.html.twig', 
            [ 
                "menu_courant" =>   "restomalin",
                'annee_max' =>      date( 'Y' ),
                'annee_courante' => $anneeCourante,
                'stats_mois' =>     $repo_stats_jour->getCAMoisCourant( false ),
                'stats_periode' =>  $repo_stats_annee->getCAByAnnee( $repo_stats_mois, $anneeCourante, false )
            ]
        );
    }
}
