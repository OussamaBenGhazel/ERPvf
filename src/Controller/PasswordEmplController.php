<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PasswordEmplController extends AbstractController
{
    #[Route('/password/empl', name: 'app_password_empl')]
    public function index(): Response
    {
        return $this->render('employee/password_empl/passwordEmpl.html.twig', [
            'controller_name' => 'PasswordEmplController',
        ]);
    }
}
