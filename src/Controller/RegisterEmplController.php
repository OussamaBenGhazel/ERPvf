<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterEmplController extends AbstractController
{
    #[Route('/register/empl', name: 'app_register_empl')]
    public function index(): Response
    {
        return $this->render('employee/register_empl/registerEmpl.html.twig', [
            'controller_name' => 'RegisterEmplController',
        ]);
    }
}
