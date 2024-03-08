<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LayoutStaticEmplController extends AbstractController
{
    #[Route('/layout/static/empl', name: 'app_layout_static_empl')]
    public function index(): Response
    {
        return $this->render('employee/layout_static_empl/layoutStaticEmpl.html.twig', [
            'controller_name' => 'LayoutStaticEmplController',
        ]);
    }
}
