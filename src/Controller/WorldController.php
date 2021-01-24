<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorldController extends AbstractController
{
    /**
     * @Route("/world", name="world")
     * @IsGranted("ROLE_USER")
     */
    public function index(): Response
    {
        return $this->render('world/index.html.twig', [
            'controller_name' => 'WorldController',
        ]);
    }
}
