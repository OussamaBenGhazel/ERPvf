<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Form\FournisseurType;
use App\Repository\FournisseurRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/fournisseur')]
class FournisseurController extends AbstractController
{
    #[Route('/', name: 'app_fournisseur_index', methods: ['GET'])]
public function index(FournisseurRepository $fournisseurRepository, Request $request, PaginatorInterface $paginator): Response
{
    // Get the current sorting criteria from the request
    $sort = $request->query->get('sort', 'id');
    $direction = $request->query->get('direction', 'asc');

    // Get fournisseurs based on sort and direction
    $fournisseurs = $fournisseurRepository->findAllSorted($sort, $direction);

    // Paginate the results
    $pagination = $paginator->paginate(
        $fournisseurs,
        $request->query->getInt('page', 1),
        10 // Items per page
    );

    // Pass variables to the Twig template
    return $this->render('fournisseur/index.html.twig', [
        'pagination' => $pagination,
        'sort' => $sort,
        'direction' => $direction,
    ]);
}

    #[Route('/pdf/{type}/{page}', name: 'app_fournisseur_pdf', methods: ['GET'])]
public function pdf(Request $request,FournisseurRepository $fournisseurRepository,PaginatorInterface $paginator,string $type,int $page = 1): Response {
    // Set session variable to indicate PDF rendering
    $request->getSession()->set('rendering_pdf', true);

    // Retrieve all fournisseur data
    $fournisseursQuery = $fournisseurRepository->createQueryBuilder('f')
        ->orderBy('f.id', 'ASC')
        ->getQuery();

    // Determine the content based on the type of PDF requested
    $htmlContent = '';
    if ($type === 'all') {
        // Fetch all fournisseur data
        $fournisseurs = $fournisseursQuery->getResult();

        // Generate HTML for all pages
        // Add table headers
        $htmlContent .= '<table class="table">';
        $htmlContent .= '<thead>';
        $htmlContent .= '<tr>';
        $htmlContent .= '<th>ID</th>';
        $htmlContent .= '<th>Nom</th>';
        $htmlContent .= '<th>Adresse</th>';
        $htmlContent .= '<th>Numdetel</th>';
        $htmlContent .= '<th>Email</th>';
        $htmlContent .= '<th>Societe</th>';
        // Add more column names as needed
        $htmlContent .= '</tr>';
        $htmlContent .= '</thead>';
        // Add table rows
        $htmlContent .= '<tbody>';
        foreach ($fournisseurs as $fournisseur) {
            $htmlContent .= '<tr>';
            $htmlContent .= '<td>' . $fournisseur->getId() . '</td>';
            $htmlContent .= '<td>' . $fournisseur->getNom() . '</td>';
            $htmlContent .= '<td>' . $fournisseur->getAdresse() . '</td>';
            $htmlContent .= '<td>' . $fournisseur->getNumdetel() . '</td>';
            $htmlContent .= '<td>' . $fournisseur->getEmail() . '</td>';
            $htmlContent .= '<td>' . $fournisseur->getSociete()->getSociete() . '</td>'; // Assuming Societe is a relation
            // Add more td elements for additional columns if needed
            $htmlContent .= '</tr>';
        }
        $htmlContent .= '</tbody>';
        $htmlContent .= '</table>';
    } elseif ($type === 'current') {
        // Paginate the fournisseur data for the current page only
        $pagination = $paginator->paginate(
            $fournisseursQuery,
            $page,
            10 // Items per page
        );

        // Generate HTML for the current page
        // Add table headers
        $htmlContent .= '<table class="table">';
        $htmlContent .= '<thead>';
        $htmlContent .= '<tr>';
        $htmlContent .= '<th>ID</th>';
        $htmlContent .= '<th>Nom</th>';
        $htmlContent .= '<th>Adresse</th>';
        $htmlContent .= '<th>Numdetel</th>';
        $htmlContent .= '<th>Email</th>';
        $htmlContent .= '<th>Societe</th>';
        // Add more column names as needed
        $htmlContent .= '</tr>';
        $htmlContent .= '</thead>';
        // Add table rows for the current page
        $htmlContent .= '<tbody>';
        foreach ($pagination->getItems() as $fournisseur) {
            $htmlContent .= '<tr>';
            $htmlContent .= '<td>' . $fournisseur->getId() . '</td>';
            $htmlContent .= '<td>' . $fournisseur->getNom() . '</td>';
            $htmlContent .= '<td>' . $fournisseur->getAdresse() . '</td>';
            $htmlContent .= '<td>' . $fournisseur->getNumdetel() . '</td>';
            $htmlContent .= '<td>' . $fournisseur->getEmail() . '</td>';
            $htmlContent .= '<td>' . $fournisseur->getSociete()->getSociete() . '</td>'; // Assuming Societe is a relation
            // Add more td elements for additional columns if needed
            $htmlContent .= '</tr>';
        }
        $htmlContent .= '</tbody>';
        $htmlContent .= '</table>';
    }

    // Generate PDF
    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');
    $dompdf = new Dompdf($pdfOptions);
    $dompdf->loadHtml($htmlContent);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $pdfContent = $dompdf->output();

    // Determine the filename based on the type of PDF requested
    $filename = $type === 'all' ? "fournisseurs_all.pdf" : "fournisseurs_page_{$page}.pdf";

    // Return the PDF as a response
    return new Response($pdfContent, Response::HTTP_OK, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => "inline; filename=\"{$filename}\"",
    ]);
}

    #[Route('/search', name: 'search_fournisseur', methods: ['GET'])]
    public function search(FournisseurRepository $fournisseurRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $query = $request->query->get('query');
    
        // Build your query with search criteria
        $queryBuilder = $fournisseurRepository->createQueryBuilder('f')
            ->leftJoin('f.societe', 's') // Assuming societe is the association with Fournisseur
            ->where('f.nom LIKE :query')
            ->orWhere('f.adresse LIKE :query')
            ->orWhere('f.numdetel LIKE :query')
            ->orWhere('f.email LIKE :query')
            ->orWhere('s.societe LIKE :query') // Assuming Societe has a field named "name" to search on
            ->setParameter('query', '%'.$query.'%');
    
        // Paginate the query results
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1), // Current page number
            10 // Items per page
        );
    
        // Pass the sort variable to the template
        $sort = $request->query->get('sort', 'id');
        $direction = $request->query->get('direction', 'asc');
    
        return $this->render('fournisseur/index.html.twig', [
            'pagination' => $pagination,
            'sort' => $sort,
            'direction' => $direction
        ]);
    }
    
    #[Route('/new', name: 'app_fournisseur_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $fournisseur = new Fournisseur();
    $form = $this->createForm(FournisseurType::class, $fournisseur);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($fournisseur);
        $entityManager->flush();

        return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
    }

    // If the form is not valid, render the form with validation errors
    return $this->renderForm('fournisseur/new.html.twig', [
        'fournisseur' => $fournisseur,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_fournisseur_show', methods: ['GET'])]
    public function show(Fournisseur $fournisseur): Response
    {
        return $this->render('fournisseur/show.html.twig', [
            'fournisseur' => $fournisseur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fournisseur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fournisseur $fournisseur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fournisseur/edit.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fournisseur_delete', methods: ['POST'])]
    public function delete(Request $request, Fournisseur $fournisseur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fournisseur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fournisseur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
