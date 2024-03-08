<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PasswordController extends AbstractController
{
    #[Route('/password', name: 'app_password')]
    public function index(): Response
    {
        return $this->render('admin/password/password.html.twig', [
            'controller_name' => 'PasswordController',
        ]);
    }
}
