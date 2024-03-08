<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChartsController extends AbstractController
{
    #[Route('/charts', name: 'app_charts')]
    public function index(): Response
    {
        return $this->render('admin/charts/charts.html.twig', [
            'controller_name' => 'ChartsController',
        ]);
    }
}
