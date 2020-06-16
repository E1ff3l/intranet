<?php

namespace App\Controller\Back;

use App\Entity\Hosting;
use App\Form\HostingType;
use App\Repository\ClientRepository;
use App\Repository\HostingRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HostingController extends AbstractController
{
    
    /**
     * Permet de lister les hébergements 
     *
     * @param Request $request
     * @param HostingRepository $repo_hosting
     * @return void
     */
    public function lister(
        Request $request, 
        HostingRepository $repo_hosting
    )
    {
        $hostings =                 $repo_hosting->findBy( [], [ 'fin' => 'ASC' ] );
        $liste_hostings =           '';

        // ---- Préparation de l'affichage -------------------------------- //
        foreach ( $hostings as $key => $hosting ) {
            
            $liste_hostings .= '<tr>';
            $liste_hostings .= '    <td>' . $hosting->getId() . '</td>';
            $liste_hostings .= '    <td><a href="' . $this->generateUrl( 'bo_hosting_edition', [ 'id' => $hosting->getId() ] ) . '">' . $hosting->getSite() . '</a></td>';
            $liste_hostings .= '    <td>';

            $liste_hostings .= ( !is_null( $hosting->getClient() ) )
                ? '<a href="' . $this->generateUrl( 'bo_client_edition', [ 'id' => $hosting->getClient()->getId() ]  ) . '">' . $hosting->getClient()->getSociete() . '</a>'
                : '&nbsp;';

            $liste_hostings .= '    </td>';

            $now =                  new \DateTime();
            $diff =                 $hosting->getFin()->diff( $now )->days;
            dump( $diff );

            // ---- Fin de période dans moins de 60 jours...
            if ( $diff <= 30 )      $style = 'label-danger';
            else if ( $diff <= 60 ) $style = 'label-warning';
            else                    $style = 'label-success';

            $liste_hostings .= '    <td><span class="label ' . $style . '">' . $hosting->getFin()->format( 'd/m/Y' ) . '</span></td>';
            $liste_hostings .= '    <td>' . $hosting->getPrix() . ' €</td>';
            $liste_hostings .= '    <td>';
            $liste_hostings .= '        <div class="action-buttons text-right">';
            $liste_hostings .= '            <a href="' . $this->generateUrl( 'bo_hosting_edition', [ 'id' => $hosting->getId() ] ) . '" class="table-actions"><i class="fa fa-edit ttip" title="Modifier cet hébergement"></i></a>';
            $liste_hostings .= '            <a href="javascript:void(0);" data-id="' . $hosting->getId() . '" class="table-actions supprimer"><i class="fa fa-times ttip" title="Supprimer cet hébergement"></i></a>';
            $liste_hostings .= '        </div>';
            $liste_hostings .= '    </td>';
            $liste_hostings .= '</tr>';
        }
        // ---------------------------------------------------------------- //

        $style =                    'success';
        return $this->render(
            'back/hosting/liste.html.twig', 
            [ 
                "menu_courant" =>   "hosting",
                'liste_hostings' => $liste_hostings
            ]
        );
    }

    /**
     * Permet d'ajouter ou de modifier un hébergement
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @param ClientRepository $repo_client
     * @param HostingRepository $repo_hosting
     * @param [type] $id
     * @return void
     */
    public function editer(
        Request $request, ObjectManager $manager, 
        ClientRepository $repo_client, HostingRepository $repo_hosting, 
        $id
    ) {

        $options =              array();

        // ---- Création d'un nouvel hébergement -------------------------- //
        if ( $id == 0 ) {
            $hosting =              new Hosting();
            $nom_hosting =          "Nouvel hébergement";
            $texte_btn =            "Ajouter";
        }
        // ---------------------------------------------------------------- //

        // ---- Modification d'un hébergement ----------------------------- //
        else {
            $hosting =              $repo_hosting->find( $id );
            $nom_hosting =          $hosting->getSite();
            $texte_btn =            "Modifier";

            if ( !is_null( $hosting->getClient() ) ) {
                $options =              [ 
                    'client' => $repo_client->find( $hosting->getClient()->getId() )
                ];
            }
        }
        // ---------------------------------------------------------------- //

        $form =                     $this->createForm(HostingType::class, $hosting, $options );
        
        //dump( $request );
        $form->handleRequest ($request );
        //$form_file->handleRequest( $request );

        // ---- Gestion des informations ---------------------------------- //
        if ( $form->isSubmitted() && $form->isValid() ) {
            $manager->persist( $hosting );
            $manager->flush();

            $this->addFlash(
                "success",
                "Hébergement modifié avec succès!"
            );

            return $this->redirectToRoute( "bo_hosting_liste" );
        }
        // ---------------------------------------------------------------- //

        return $this->render(
            '/back/hosting/ajout.html.twig',
            [
                "menu_courant" =>   "hosting",
                "id_hosting" =>     $id,
                "nom_hosting" =>    $nom_hosting,
                "texte_btn" =>      $texte_btn,
                'form' =>           $form->createView()
            ]
        );
    }

    /**
     * Permet de supprimer un hébergement
     *
     * @param ObjectManager $manager
     * @param HostingRepository $repo_hosting
     * @param [type] $id
     * @return void
     */
    public function supprimer(
        ObjectManager $manager, 
        HostingRepository $repo_hosting, 
        $id
    ) {

        // ---- Chargement de l'hébergement à supprimer ------------------- //
        $hosting =              $repo_hosting->find( $id );

        // ---- Hébergement introuvable ----------------------------------- //
        if ( is_null( $hosting ) ) {
            $this->addFlash(
                "danger",
                "Impossible de supprimer cet hébergement..."
            );

            return $this->redirectToRoute( "bo_hosting_liste" );
        }
        // ---------------------------------------------------------------- //

        // ---- Suppression de l'hébergement ------------------------------ //
        else {
            $manager->remove( $hosting );
            $manager->flush();

            $this->addFlash(
                "success",
                "Hébergement supprimé avec succès!"
            );

            return $this->redirectToRoute( "bo_hosting_liste" );
        }
        // ---------------------------------------------------------------- //

    }
}
