<?php

namespace App\Controller\Back;

use App\Entity\ProjetFichier;
use App\Repository\ProjetRepository;
use App\Repository\ProjetFichierRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjetFichierController extends AbstractController
{
    
    /**
     * Upload et enregistrement physique des fichiers
     *
     * @param Request $request
     * @return void
     */
    public function uploader(
        Request $request
    ) {
        //dump( $request );
        //dump( $request->request );
        //dump( $request->files->get( "file" ) );

        $debug =                    false;
        $_file =                    $request->files->get( "file" );
        dump( $_file );

        // ---- Traitement du fichier ------------------------------------- //
        if( 1 == 1 ) {
            
            // ---- Le fichier existe ------------------------------------- //
            //dd( "--- " . $_file->isValid() );
            if ( $_file->isValid() ) {
                
                // ---- Traitement du fichier original ---------------- //
                if ( 1 == 1 ) {
                    if ( $debug ) dump( "--> Traitement du fichier '" . $_file->getClientOriginalName() );
                    
                    $chemin_destination =	$_SERVER[ "DOCUMENT_ROOT" ] . "fichier/";
                    
                    // ---- Extension du fichier ---------------------- //
                    $info_fichier = 		pathinfo( htmlentities( $_file->getClientOriginalName() ) );
                    //dd( $info_fichier );
                    
                    $ext = 					strtolower( $info_fichier[ "extension" ] );
                    //$nom_fichier = 			strtotime( "now" );
                    $nom_fichier = 			strtolower( $info_fichier[ "filename" ] );
                    //echo "-->" . $nom_fichier;
                    
                    // ---- Pour éviter d'écraser l'ancien en cas de doublon
                    $tiret = 				"";
                    $n = 					"";
                    while( file_exists( $chemin_destination . $nom_fichier . $tiret . $n . "." . $ext ) ) {
                        if ( $debug ) echo "While... \n";
                        $tiret = 			"_";
                        $n = 				( $n == "" ) ? 1 : $n + 1;
                    }
                    
                    $nom_fichier = 			$nom_fichier . $tiret . $n . "." . $ext;
                    if ( $debug ) echo "Nom du fichier final : " . $nom_fichier . "<br>\n";
                    
                    $_file->move( $chemin_destination, $nom_fichier );
                }
                // ---------------------------------------------------- //
                
                // ---- Tout est ok!!! -------------------------------- //
               return new JsonResponse( array(
                    'error' =>			    false, 
                    'fichier' =>			$nom_fichier, 
                    'ext' =>				$ext, 
                    'fichier_long' =>       $chemin_destination . "" . $nom_fichier
                ), 200 );
            }
            // ------------------------------------------------------------ //

            // ---- Erreur lors de l'upload ------------------------------- //
            else {

                switch ( $_file->getError() ){    
                    case 1: // UPLOAD_ERR_INI_SIZE
                        $erreur =	true;
                        $message= 	"Le fichier \"" . $_file->getClientOriginalName() . "\" dépasse la limite autorisée par le serveur.";
                        break;    
                    
                    case 2: // UPLOAD_ERR_FORM_SIZE
                        $erreur = 	true;
                        $message= 	"Le fichier \"" . $_file->getClientOriginalName() . "\" dépasse la limite autorisée dans le formulaire HTML.";
                        break;    
                    
                    case 3: // UPLOAD_ERR_PARTIAL
                        $erreur = 	true;
                        $message= 	"L'envoi du fichier \"" . $_file->getClientOriginalName() . "\" a été interrompu pendant le transfert.";
                        break;    
                    
                    case 4: // UPLOAD_ERR_NO_FILE
                        $erreur = 	true;
                        $message= 	"Le fichier \"" . $_file->getClientOriginalName() . "\" a une taille nulle.";
                        break;    
                } 
                // -------------------------------------------------------- //
                
            }
		
        }
        // ---------------------------------------------------------------- //

        return new JsonResponse( array(
            'error' =>			    $erreur, 
            'message' =>            $message
        ), 200 );
    }
    
    /**
     * Ajoute en base le fichier uploadé
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @param ProjetRepository $repo_projet
     * @param ProjetFichierRepository $repo_fichier
     * @return void
     */
    public function ajouter(
        Request $request, ObjectManager $manager, 
        ProjetRepository $repo_projet, ProjetFichierRepository $repo_fichier
    ) {
        //dd( $request->request );

        $id_projet =                $request->request->get( "id_projet" );
        $nom_fichier =              $request->request->get( "fichier" );

        // ---- Chargement du projet courant ------------------------------ //
        $projet =                   $repo_projet->find( $id_projet );

        // ---- Ajout en base du nouveau fichier -------------------------- //
        if ( 1 == 1 ) {
            $projetFichier =        new ProjetFichier();
            $projetFichier->setFichier( $nom_fichier )
                ->setProjet( $projet );
            
            $manager->persist( $projetFichier );
            $manager->flush();
        }
        // ---------------------------------------------------------------- //
        
        // ---- Liste des fichiers associés au projet ----------------- //
        $fichiers =             $repo_fichier->findBy(
            [ 'projet' =>       $id_projet ],
            [ 'id' =>   'DESC' ]
        );

        return new JsonResponse( array(
            'error' =>          false,
            'html' =>           $this->renderView(
                'back/projet/fichier_liste.html.twig', 
                [
                    'fichiers' => $fichiers
                ]
            )
        ), 200 );
    }
    
    /**
     * Supprime un fichier préalablement uploadé
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @param ProjetRepository $repo_projet
     * @param ProjetFichierRepository $repo_fichier
     * @return void
     */
    public function supprimer( 
        Request $request, ObjectManager $manager, 
        ProjetRepository $repo_projet, ProjetFichierRepository $repo_fichier
    )
    {
        //dd( $request->request );
        $id =                   $request->request->get( "num_fichier" );
        $projet_id =            $request->request->get( "num_projet" );

        $projet =               $repo_projet->find( $projet_id );
        $projetFichier =        $repo_fichier->find( $id );
        //dd( $projetFichier );

        // ---- Suppression du fichier (Physiquement) ----------------- //
        if ( file_exists( $_SERVER[ "DOCUMENT_ROOT" ] . "fichier/" . $projetFichier->getFichier() ) ) {
            unlink( $_SERVER[ "DOCUMENT_ROOT" ] . "fichier/" . $projetFichier->getFichier() );
        }
        
        // ---- Suppression du fichier (En base) ---------------------- //
        $manager->remove( $projetFichier );
        $manager->flush();

        // ---- Liste des fichiers associés au projet ----------------- //
        $fichiers =             $repo_fichier->findBy(
            [ 'projet' =>       $projet_id ],
            [ 'id' =>   'DESC' ]
        );

        return new JsonResponse( array(
            'message' =>        "Fichier supprimé",
            'id_projet' =>      $projet_id,
            "html" =>           $this->renderView(
                'back/projet/fichier_liste.html.twig', 
                [
                    'fichiers' => $fichiers
                ]
            )
        ), 200 );
    }
}
