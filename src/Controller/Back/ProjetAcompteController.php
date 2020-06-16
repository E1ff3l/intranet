<?php

namespace App\Controller\Back;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProjetAcompteController extends AbstractController
{
    /**
     * @Route("/projet/acompte", name="projet_acompte")
     */
    public function index()
    {
        return $this->render('projet_acompte/index.html.twig', [
            'controller_name' => 'ProjetAcompteController',
        ]);
    }
}
