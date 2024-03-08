<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LayoutStaticController extends AbstractController
{
    #[Route('/layout/static', name: 'app_layout_static')]
    public function index(): Response
    {
        return $this->render('admin/layout_static/layoutStatic.html.twig', [
            'controller_name' => 'LayoutStaticController',
        ]);
    }
}
