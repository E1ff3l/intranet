<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PlafondController extends AbstractController
{
    /**
     * @Route("/plafond", name="plafond")
     */
    public function index()
    {
        return $this->render('plafond/index.html.twig', [
            'controller_name' => 'PlafondController',
        ]);
    }
}
