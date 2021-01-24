<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{
    /**
     * @Route("/welcome", name="welcome")
     */
    public function index(): Response
    {
        $doShit = [
            "Till" => "Das Vocalist",
            "Ollie" => "Bassman",
            "Flake" => "Rainbow Pianoman",
            "Reesh" => "Sexy Elfboi",
            "Schneider" => "Sexually Confusing",
            "Paul" => "Smol"
        ];

        return $this->render('welcome/index.html.twig', [
            'controller_name' => 'WelcomeController',
            'rammstein' => $doShit
        ]);
    }
}
