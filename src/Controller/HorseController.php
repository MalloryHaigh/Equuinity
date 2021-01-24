<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HorseController extends AbstractController
{
    #[Route('/horse', name: 'horse')]
    public function index(): Response
    {
        return $this->render('horse/index.html.twig', [
            'controller_name' => 'HorseController',
        ]);
    }
}
