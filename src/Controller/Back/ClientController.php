<?php

namespace App\Controller\Back;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\PaysRepository;
use App\Repository\ClientRepository;
use App\Repository\ProjetRepository;
use App\Repository\ProjetEtatRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
    
    /**
     * Liste les utilisateurs ACTIFS disponibles
     *
     * @param ClientRepository $repo
     * @return void
     */
    public function lister(ClientRepository $repo)
    {
        $clients =                  $repo->findBy(
            [ "online" => true ]
        );

        return $this->render(
            'back/client/liste.html.twig', 
            [ 
                "menu_courant" =>   "client",
                'clients' =>        $clients 
            ]
        );
    }

    /**
     * Liste les utilisateurs INACTIFS disponibles
     *
     * @param ClientRepository $repo
     * @return void
     */
    public function liste_inactif(ClientRepository $repo)
    {
        $clients =                  $repo->findBy(
            [ "online" => false ]
        );
        //dump( $clients );

        return $this->render(
            'back/client/liste.html.twig', 
            [ 
                "menu_courant" =>   "client",
                'clients' =>        $clients 
            ]
        );
    }

    /**
     * Modifier un client de la base de données
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @param PaysRepository $repo_pays
     * @param ClientRepository $repo_client
     * @param ProjetRepository $repo_projet
     * @param [type] $id
     * @return void
     */
    public function editer(
        Request $request, ObjectManager $manager, 
        PaysRepository $repo_pays, ClientRepository $repo_client, ProjetRepository $repo_projet, 
        $id
    ) {

        // ---- Création d'un nouveau client ------------------------------ //
        if ( $id == 0 ) {
            $client =               new Client();
            $nom_client =           "Nouveau client";
            $texte_btn =            "Ajouter";
            $projets =              array();
            $options =              [ 'pays' => $repo_pays->find( 73 ) ];   // Sélection de la France par défaut
        }
        // ---------------------------------------------------------------- //

        // ---- Modification d'un client ---------------------------------- //
        else {
            $client =               $repo_client->find($id);
            $nom_client =           $client->getSociete() . " (<i>" . $client->getNom() . " " . $client->getPrenom() . "</i>)";
            $texte_btn =            "Modifier";

            // ---- Liste des projets du client --------------------------- //
            $projets =              $repo_projet->findBy(
                [ 'client' =>       $id ],
                [ 'dateSaisie' =>   'DESC' ]
            );

            $options =              [ 'pays' => $repo_pays->find( $client->getPays()->getId() ) ];
        }
        // ---------------------------------------------------------------- //

        $form =                     $this->createForm(ClientType::class, $client, $options );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($client);
            $manager->flush();

            $this->addFlash(
                "success",
                "Client modifié avec succès!"
            );

            return $this->redirectToRoute( "bo_client_liste" );
        }

        return $this->render(
            '/back/client/ajout.html.twig',
            [
                "menu_courant" =>   "client",
                "nom_client" =>     $nom_client,
                "texte_btn" =>      $texte_btn,
                "projets" =>        $projets,
                'form' =>           $form->createView()
            ]
        );
    }
}

