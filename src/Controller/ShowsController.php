<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowsController extends AbstractController
{
    /**
     * @Route("/shows", name="shows")
     * @IsGranted("ROLE_USER")
     */
    public function index(): Response
    {
        return $this->render('shows/index.html.twig', [
            'controller_name' => 'ShowsController',
        ]);
    }
}
