<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LayoutSidenavLightEmplController extends AbstractController
{
    #[Route('/layout/sidenav/light/empl', name: 'app_layout_sidenav_light_empl')]
    public function index(): Response
    {
        return $this->render('employee/layout_sidenav_light_empl/layoutSidenavLightEmpl.html.twig', [
            'controller_name' => 'LayoutSidenavLightEmplController',
        ]);
    }
}
