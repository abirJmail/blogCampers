<?php

namespace App\Controller;

use App\Entity\Destination;
use App\Form\Destination1Type;
use App\Repository\DestinationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/destination')]
class DestinationController extends AbstractController
{
    #[Route('/', name: 'app_destination_index', methods: ['GET'])]
    public function index(DestinationRepository $destinationRepository): Response
    {
        return $this->render('destination/index.html.twig', [
            'destinations' => $destinationRepository->findAll(),
        ]);
    }

    // #[Route('/new', name: 'app_destination_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $destination = new Destination();
    //     $form = $this->createForm(Destination1Type::class, $destination);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($destination);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_destination_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('destination/new.html.twig', [
    //         'destination' => $destination,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_destination_show', methods: ['GET'])]
    public function show(Destination $destination): Response
    {
        $articles = $destination->getArticles();
        $categories = $destination->getCategories();
        return $this->render('destination/show.html.twig', [
            'destination' => $destination,
            'articles' => $articles,
            'categories' =>$categories,
        ]);
    }

        
    // #[Route('/{id}/edit', name: 'app_destination_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Destination $destination, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(Destination1Type::class, $destination);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_destination_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('destination/edit.html.twig', [
    //         'destination' => $destination,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_destination_delete', methods: ['POST'])]
    // public function delete(Request $request, Destination $destination, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$destination->getId(), $request->request->get('_token'))) {
    //         $entityManager->remove($destination);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_destination_index', [], Response::HTTP_SEE_OTHER);
    // }
}
