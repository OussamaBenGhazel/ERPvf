<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LayoutSidenavLightController extends AbstractController
{
    #[Route('/layout/sidenav/light', name: 'app_layout_sidenav_light')]
    public function index(): Response
    {
        return $this->render('admin/layout_sidenav_light/layoutSidenavLight.html.twig', [
            'controller_name' => 'LayoutSidenavLightController',
        ]);
    }
}
