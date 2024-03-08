<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Validator\Validator\ValidatorInterface;



#[Route('/client')]
class ClientController extends AbstractController
{

    #[Route('/', name: 'app_client_index', methods: ['GET'])]
public function index(ClientRepository $clientRepository, Request $request): Response
{
    $query = $request->query->get('query');
    $sort = $request->query->get('sort');
    
    if ($query) {
        $clients = $clientRepository->findBySearchQuery($query);
    } else {
        // If no search query, retrieve all clients
        $clients = $clientRepository->findAll();
    }

    // Apply sorting logic
    if ($sort === 'desc') {
        usort($clients, function ($a, $b) {
            return $b->getId() <=> $a->getId();
        });
    } else {
        usort($clients, function ($a, $b) {
            return $a->getId() <=> $b->getId();
        });
    }

    return $this->render('client/index.html.twig', [
        'clients' => $clients,
    ]);
}
    


#[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, ValidatorInterface $validator): Response
{
    $client = new Client();
    $form = $this->createForm(ClientType::class, $client);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($client);
        $entityManager->flush();

        $email = (new Email())
            ->from('benaissarihem036@gmail.com')
            ->to('oussama.ben.ghazel09@gmail.com')
            ->subject('Nouveau client Ajouté')
            ->text('!!')
            ->html('<p>Le client ' . $client->getNom() . ' est ajouté avec succès</p>');

        $mailer->send($email);

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }

    // Validate the client entity manually
    $errors = $validator->validate($client);
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            // Add each error message to a flash message
            $this->addFlash('error', $error->getMessage());
        }
    }

    return $this->renderForm('client/new.html.twig', [
        'client' => $client,
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
