<?php
namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClientRepository;

class PdfController extends AbstractController
{
    /**
     * @Route("/generate-pdf", name="generate_pdf")
     */
    public function generatePdf(ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->findAll(); 
        
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($pdfOptions);


        $html = $this->renderView('client/index.html.twig', [
            'clients' => $clients,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();

        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public/pdf/';
        $pdfPath = $publicDirectory . 'clients.pdf';

        file_put_contents($pdfPath, $output);


        return new BinaryFileResponse($pdfPath);
    }
}
