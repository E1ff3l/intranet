<?php

namespace App\Repository;

use App\Entity\Projet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Projet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Projet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Projet[]    findAll()
 * @method Projet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Projet::class);
    }

    /**
     * Retourne le CA pour un état, une année et un mois donnés
     *
     * @param integer $num_etat
     * @param integer $annee
     * @param boolean $debug
     * @return void
     */
    public function findCAByEtatAnnee( $num_etat=6, $annee=2019, $mois=1, $debug=false )
    {
        $_mois =                     ( $mois > 0 && $mois <= 12 ) ? $mois : 1;
        if ( $_mois < 10 )           $_mois = '0' . $_mois;

        // ---- Résultats sur l'année entière --------------------- //
        if ( $mois == 0 ) {
            $debut =                new \DateTime( $annee . '-' . $_mois . '-01' );
            $fin =                  new \DateTime( ( $annee + 1 ) . '-' . $_mois . '-01' );
        }
        // -------------------------------------------------------- //

        // ---- Résultats sur un mois donné ----------------------- //
        else {
            if ( $mois == 12 ) {
                $_mois_fin =        '01';
                $annee_fin =        $annee + 1;
            }
            else {
                $_mois_fin =       ( $mois > 0 && $mois <= 8 ) ? '0' . ( $mois + 1 ) : ( $mois + 1 );
                $annee_fin =        $annee;
            }
            
            $debut =                new \DateTime( $annee . '-' . $_mois . '-01' );
            $fin =                  new \DateTime( $annee_fin . '-' . $_mois_fin . '-01' );
        }
        // -------------------------------------------------------- //

        $requete = $this->createQueryBuilder('p')
            ->select( 'sum(p.prix) as total' )
            ->andWhere('p.projetEtat = :num_etat')
            ->setParameter( 'num_etat', $num_etat )
            ->andWhere('p.datePaiement >= :debut')
            ->setParameter( 'debut', $debut )
            ->andWhere('p.datePaiement < :fin')
            ->setParameter( 'fin', $fin )
            ->getQuery()
        ;
        if ( $debug )   dump( $requete );

        return $requete->getResult();
    }

    /**
     * Retourne la projection du CA pour une année donnée
     *
     * @param integer $annee
     * @param boolean $debug
     * @return void
     */
    public function findProjectionCAByAnnee( $annee=2019, $debug=false )
    {
        $debut =                    new \DateTime( $annee . '-01-01' );
        $fin =                      new \DateTime( ( $annee + 1 ) . '-01-01' );

        $requete = $this->createQueryBuilder('p')
            ->select( 'sum(p.prix) as total' )
            ->andWhere('p.datePaiement >= :debut AND p.datePaiement < :fin AND p.projetEtat = 6')
            ->orWhere('p.datePaiement IS NULL AND p.projetEtat != 6 AND p.projetEtat >= 1 AND p.projetEtat <= 7')
            ->setParameter( 'debut', $debut )
            ->setParameter( 'fin', $fin )
            ->getQuery()
        ;
        if ( $debug )   dump( $requete );
        
        return $requete->getResult();
    }

    public function findBestClients( $debug=false ) {
        
        $requete = $this->createQueryBuilder('p')
            ->select( 'SUM(p.prix) AS total, COUNT(p) AS nb, c.societe' )
            ->andWhere('p.projetEtat = 6')
            ->join('p.client', 'c')
            ->groupBy('p.client')
            ->orderBy('total', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
        ;
        if ($debug)     dump($requete);

        return $requete->getResult();
    }

    public function getProjetsWithFacture( $num_etat, $debug=false ) {
        $projets =                  ($num_etat == '')
            ? $this->findAll()
            : $this->findBy([ 'projetEtat' => $num_etat ]);

        // ---- Pour chaque projet, on détermine s'il y a un fichier facture au format PDF (donc généré par le site!)
        foreach ( $projets as $key => $_projet ) {
            //dump($_projet);
            $_projet->setFichierFacture( '' );

            // ---- Projet "Facturé" ou "Facturé & payé" ------------------ //
            if ( $_projet->getProjetEtat()->getId() == 4 || $_projet->getProjetEtat()->getId() == 6 ) {
                //dump( $_projet );

                // ---- Recherche d'un fichier au format "facture_yyyymmdd1234.pdf"
                if ( 1 == 1 ) {
                    $indice =           ( $_projet->getIndice() < 1000 ) ? '0' : '';
                    $fichier_facture =  'facture_' . $_projet->getDateFacturation()->format( 'Ymd' ) . $indice . $_projet->getIndice() . '.pdf';
                    //echo "--- Recherche de " . $fichier_facture . "<br>";

                    if ( file_exists( $_SERVER[ "DOCUMENT_ROOT" ] . "fichier/" . $fichier_facture ) ) {
                        $_projet->setFichierFacture( $fichier_facture );
                    }
                }
                // -------------------------------------------------------- //

            }
            // ------------------------------------------------------------ //
        }
        // ---------------------------------------------------------------- //
        dump($projets);

        return $projets;
    }

    // /**
    //  * @return Projet[] Returns an array of Projet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Projet
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
