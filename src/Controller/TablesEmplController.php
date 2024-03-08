<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TablesEmplController extends AbstractController
{
    #[Route('/tables/empl', name: 'app_tables_empl')]
    public function index(): Response
    {
        return $this->render('employee/tables_empl/tablesEmpl.html.twig', [
            'controller_name' => 'TablesEmplController',
        ]);
    }
}
