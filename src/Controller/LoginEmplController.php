<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginEmplController extends AbstractController
{
    #[Route('/login/empl', name: 'app_login_empl')]
    public function index(): Response
    {
        return $this->render('employee/login_empl/loginEmpl.html.twig', [
            'controller_name' => 'LoginEmplController',
        ]);
    }
}
