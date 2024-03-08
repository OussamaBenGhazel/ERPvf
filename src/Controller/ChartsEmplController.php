<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChartsEmplController extends AbstractController
{
    #[Route('/charts/empl', name: 'app_charts_empl')]
    public function index(): Response
    {
        return $this->render('employee/charts_empl/chartsEmpl.html.twig', [
            'controller_name' => 'ChartsEmplController',
        ]);
    }
}
