<?php

namespace App\Repository;

use App\Entity\StatRmMois;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method StatRmMois|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatRmMois|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatRmMois[]    findAll()
 * @method StatRmMois[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatRmMoisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatRmMois::class);
    }

	/**
     * Retourne la liste des statistiques du mois donné
     *
     * @param [type] $anneeCourante
     * @param boolean $debug
     * @return void
     */
    public function getStatistiqueByMois( $moisCourant, $debug=false ) {
		$moisProchain = 		new \DateTime( $moisCourant->format( 'Y-m' ) . '-01' );
		$moisProchain = 		$moisProchain->add( new \DateInterval( 'P1M' ) );
		if ($debug) echo "--->" . $moisCourant->format( 'd/m/Y' ) . ' / ' . $moisProchain->format( 'd/m/Y' ) . '<br>';

        $requete = 				$this->createQueryBuilder('s')
			->select('
				sum(s.commission) as commission, sum(s.forfait) as forfait, sum(s.ca_total) as ca_total, sum(s.ca_variable) as ca_variable, sum(s.ca_forfait) as ca_forfait,
				sum(s.mode_livraison) as mode_livraison, sum(s.mode_ae) as mode_ae, 
				sum(s.commande_midi) as commande_midi, sum(s.commande_soir) as commande_soir, 
				sum(s.support_rm) as support_rm, sum(s.support_smart) as support_smart, sum(s.support_vit) as support_vit, sum(s.support_autre) as support_autre, 
				sum(s.client_connecte) as client_connecte, sum(s.client_express) as client_express
			')
			->andWhere('s.jour >= :debut AND s.jour < :fin')
            ->setParameter( 'debut', new \DateTime( $moisCourant->format( 'Y-m' ) . '-01 00:00:00' ) )
            ->setParameter( 'fin', $moisProchain )
            ->getQuery()
        ;
        if ($debug) dump($requete);
        
        $datas = 				$requete->getResult();
        if ($debug) dump( $datas );
        
		return $datas;
    }

	/**
     * Retourne la liste des statistiques de l'année donnée
     *
     * @param [type] $anneeCourante
     * @param boolean $debug
     * @return void
     */
    public function getStatistiqueByAnnee( $anneeCourante, $debug=false ) {
		$anneeProchaine = 		new \DateTime( $anneeCourante . '-01-01' );
		$anneeProchaine = 		$anneeProchaine->add( new \DateInterval( 'P1Y' ) );
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
            ->setParameter( 'debut', new \DateTime( $anneeCourante . '-01-01 00:00:00' ) )
            ->setParameter( 'fin', $anneeProchaine )
            ->getQuery()
        ;
        if ($debug) dump($requete);
        
        $datas = 				$requete->getResult();
		if ($debug) dump( $datas );
		
		return $datas;
    }

    // /**
    //  * @return StatRmMois[] Returns an array of StatRmMois objects
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
    public function findOneBySomeField($value): ?StatRmMois
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
