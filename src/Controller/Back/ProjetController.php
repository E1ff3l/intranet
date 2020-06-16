<?php

namespace App\Controller\Back;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Projet;
use App\Form\ProjetType;
use App\Form\AcompteType;
use App\Entity\ProjetAcompte;
use App\Entity\ProjetFichier;
use App\Form\ProjetFichierType;
use App\Repository\ClientRepository;
use App\Repository\ProjetRepository;
use App\Repository\ProjetEtatRepository;
use App\Repository\InfoDiverseRepository;
use App\Repository\ProjetAcompteRepository;

// Include Dompdf required namespaces
use App\Repository\ProjetFichierRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjetController extends AbstractController
{

   /**
    * Permet de lister les projets
    *
    * @param ProjetRepository $repo
    * @return void
    */ 
    public function lister(
        Request $request, 
        ProjetEtatRepository $repo_etat, ProjetRepository $repo_projet,
        $num_etat
    )
    {
        $etats =                    $repo_etat->findBy( [], [ 'ordre' => 'ASC'] );
        $projets =                  $repo_projet->getProjetsWithFacture( $num_etat, false );
        //dd($projets);

        return $this->render(
            'back/projet/liste.html.twig', 
            [ 
                "menu_courant" =>   "projet",
                'num_etat' =>       $num_etat,
                'etats' =>          $etats,
                'projets' =>        $projets 
            ]
        );
    }

    /**
     * Permet d'ajouter ou de modifier un projet
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @param InfoDiverseRepository $repo_info
     * @param ProjetEtatRepository $repo_etat
     * @param ClientRepository $repo_client
     * @param ProjetRepository $repo_projet
     * @param ProjetFichierRepository $repo_fichier
     * @param [type] $id
     * @return void
     */
    public function editer(
        Request $request, ObjectManager $manager, 
        InfoDiverseRepository $repo_info, ProjetEtatRepository $repo_etat, ClientRepository $repo_client, 
        ProjetRepository $repo_projet, ProjetFichierRepository $repo_fichier, ProjetAcompteRepository $repo_acompte, 
        $id
    ) {

        // ---- Création d'un nouveau projet ------------------------------ //
        if ( $id == 0 ) {
            $projet =               new Projet();
            $nom_projet =           "Nouveau projet";
            $texte_btn =            "Ajouter";
            $fichiers =             array();
            $options =              array();
        }
        // ---------------------------------------------------------------- //

        // ---- Modification d'un projet ---------------------------------- //
        else {
            $projet =               $repo_projet->find($id);
            $nom_projet =           $projet->getTitre();
            $texte_btn =            "Modifier";

            // ---- Liste des fichiers associés au projet ----------------- //
            $fichiers =             $repo_fichier->findBy(
                [ 'projet' =>       $id ],
                [ 'id' =>   'DESC' ]
            );

            $options =              [ 
                'etat' =>   $repo_etat->find( $projet->getProjetEtat()->getId() ),
                'client' => $repo_client->find( $projet->getClient()->getId() )
            ];
        }
        // ---------------------------------------------------------------- //

        $form =                     $this->createForm(ProjetType::class, $projet, $options );
        
        //dump( $request );
        //dump( $request->get( 'generationPdf' ) );
        $form->handleRequest ($request );

        // ---- Gestion des informations clients -------------------------- //
        if ( $form->isSubmitted() && $form->isValid() ) {
            $anneeFacturation =      ( !is_null( $projet->getDateFacturation() ) ) ? $projet->getDateFacturation()->format( 'Y' ) : 1970;
            //dd( $dateFacturation );
            $isFacturable =         ( $anneeFacturation >= 2019 && ( $projet->getProjetEtat()->getId() == 4 || $projet->getProjetEtat()->getId() == 5 || $projet->getProjetEtat()->getId() == 6 ) ) ? true : false;

            // ---- Facturation du projet --------------------------------- //
            if ( is_null( $projet->getIndice() ) && $isFacturable ) {
                $infos =            $repo_info->find( 1 );
                $nouvel_indice =    $infos->getNumLastFacture() + 1;
                
                // ---- Initialisation de l'indice de facturation --------- //
                $projet->setIndice( $nouvel_indice );

                // ---- MAJ des infos générales --------------------------- //
                $infos->setNumLastFacture( $nouvel_indice );
                $manager->persist( $infos );
            }
            // ------------------------------------------------------------ //

            // ---- Génération de la facture ------------------------------ //
            if ( $request->get( 'generationPdf' ) === '1' && $isFacturable ) {
                //dd( "Génération de la facture..." );

                // ---- Préparation des données pour le PDF --------------- //
                if ( 1 == 1 ) {
                    $num_facture =              $projet->getDateFacturation()->format( 'Ymd' );
                    $num_facture .=             ( $projet->getIndice() < 1000 ) ? '0' . $projet->getIndice() : $projet->getIndice();
                    $nom_fichier =              'facture_' . $num_facture . '.pdf';

                    $tab_mois = 				array( "", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre" );
                    $date_facturation =         ( $projet->getDateFacturation()->format( 'd' ) > 1 ) 
                        ? $projet->getDateFacturation()->format( 'd' ) . ' '
                        : '1ier ';
                    $date_facturation .=        strtolower( $tab_mois[ +$projet->getDateFacturation()->format( 'm' ) ] ) . ' ';
                    $date_facturation .=        $projet->getDateFacturation()->format( 'Y' );
                    $date_facturation_heure =   $projet->getDateFacturation()->format( 'd/m/Y' );

                    // ---- Définition du destinataire -------------------- //
                    if ( 1 == 1 ) {
                        $client =               $projet->getClient();
                        $destinataire =         '<span class="gras">' . $client->getSociete() . '</span><br />';
                        $destinataire .=        $client->getAdresse();
                        if ( !is_null( $client->getAdresseSuite() ) ) $destinataire .= '<br />' . $client->getAdresseSuite();
                        $destinataire .=        '<br />' . $client->getCp() . ' ' . $client->getVille();
                    }
                    // ---------------------------------------------------- //

                    // ---- Liste des acomptes sur le projet -------------- //
                    $acomptes =                 $repo_acompte->findBy( 
                        [ 'projet' =>			$id ],
                        [ 'datePaiement' =>		'DESC' ]
                    );
                }
                // -------------------------------------------------------- //

                // Configure Dompdf according to your needs
                $pdfOptions =       new Options();
                $pdfOptions->set( 'defaultFont', 'Arial' );

                // Instantiate Dompdf with our options
                $dompdf =           new Dompdf( $pdfOptions );

                // Retrieve the HTML generated in our twig file
                $html =             $this->renderView(
                    'back/projet/template_facture.html.twig', 
                    [
                        'num_facture' =>            $num_facture,
                        'destinataire' =>           $destinataire,
                        'date_facturation' =>       $date_facturation,
                        'date_facturation_heure' => $date_facturation_heure,
                        'titre_projet' =>           $projet->getTitre(),
                        'description_projet' =>     $projet->getDescription(),
                        'tarif' =>                  $projet->getPrix(),
                        'acomptes' =>			    $acomptes
                    ]
                );

                // Load HTML to Dompdf
                $dompdf->loadHtml( $html );

                // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
                $dompdf->setPaper( 'A4', 'portrait' );

                // Render the HTML as PDF
                $dompdf->render();

                // ---- Affichage à l'écran ------------------------------- //
                if ( $request->get( 'apercuPdf' ) === '1' ) {
                    $dompdf->stream("mypdf.pdf", [
                        "Attachment" => false
                    ]);
                }
                // -------------------------------------------------------- //

                // ---- Enregistrement du fichier ------------------------- //
                else {
                    file_put_contents( 
                        $_SERVER[ "DOCUMENT_ROOT" ] . "fichier/" . $nom_fichier, 
                        $dompdf->output()
                    );
                }
                // -------------------------------------------------------- //

                // ---- Enregistrement en base ---------------------------- //
                if ( 1 == 1 ) {

                    // ---- Recherche si le fichier existe déjà en base --- //
                    $projetFichier = $repo_fichier->findOneBy( [ "fichier" => $nom_fichier  ] );

                    // ---- Fichier inexistant --> Création! -------------- //
                    if ( is_null( $projetFichier ) ) {
                        $projetFichier = new ProjetFichier();
                        $projetFichier->setFichier( $nom_fichier )
                            ->setProjet( $projet );

                        $manager->persist( $projetFichier );
                    }
                    // ---------------------------------------------------- //

                }
                // -------------------------------------------------------- //

            }
            // ------------------------------------------------------------ //

            // ---- Gestion des acomptes ---------------------------------- //
            foreach ( $projet->getProjetAcomptes() as $acompte ) {
                //dd( $acompte );
                $acompte->setProjet( $projet );
                $manager->persist( $acompte );
            }
            // ------------------------------------------------------------ //

            $manager->persist( $projet );
            $manager->flush();

            $this->addFlash(
                "success",
                "Projet modifié avec succès!"
            );

            return $this->redirectToRoute( "bo_projet_liste", [ 'num_etat' => $projet->getProjetEtat()->getId() ] );
        }
        // ---------------------------------------------------------------- //

        return $this->render(
            '/back/projet/ajout.html.twig',
            [
                "menu_courant" =>   "projet",
                "id_projet" =>      $id,
                "nom_projet" =>     $nom_projet,
                "texte_btn" =>      $texte_btn,
                "fichiers" =>       $fichiers,
                'form' =>           $form->createView()
            ]
        );
    }


    public function dupliquer(
        Request $request, ObjectManager $manager, 
        ProjetEtatRepository $repo_etat, ProjetRepository $repo_projet, 
        $id
    ) {

        // ---- Récupération du projet à dupliquer ------------------------ //
        $projet_initial =           $repo_projet->find( $id );
        //dd($projet_initial);

        // ---- Création du nouveau projet -------------------------------- //
        $nouveau_projet =           new Projet();
        $nouveau_projet->setTitre( $projet_initial->getTitre() )
                    ->setDescription( $projet_initial->getDescription() )
                    ->setPrix( $projet_initial->getPrix() )
                    ->setClient( $projet_initial->getClient() )
                    ->setProjetEtat( $repo_etat->find( 7 ) );

        //dd($nouveau_projet);

        $manager->persist( $nouveau_projet );
        $manager->flush();

        return $this->redirectToRoute("bo_projet_edition", [
            'id' => $nouveau_projet->getId()
        ] );

    }

    public function telechargerFacture( Request $request, ProjetRepository $repo_projet, $fichier ) {
        $fichierFacture =           $_SERVER[ "DOCUMENT_ROOT" ] . "/fichier/" . $fichier;
        
        $response =                 new BinaryFileResponse( $fichierFacture );
        $response->headers->set('Content-Type', 'text/plain');
        $response->setContentDisposition( ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fichier );
        return $response;
    }
}
