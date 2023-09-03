<?php

namespace App\Controller;

use App\Entity\Destination;
use App\Form\DestinationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DestinationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin/destination')]
class AdminDestinationController extends AbstractController
{
    #[Route('/', name: 'app_admin_destination_index', methods: ['GET'])]
    public function index(DestinationRepository $destinationRepository): Response
    {
        return $this->render('admin_destination/index.html.twig', [
            'destinations' => $destinationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_destination_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $destination = new Destination();
        $form = $this->createForm(DestinationType::class, $destination);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             /**
             * TODO: Upload image
             * 1 - Récupérer l'image (dans une variable avec request) OK
             * 2 - Modifier le nom de l'image (nom unique)
             * 3 - Enregistrer l'image dans son répertoire ('articles_images') OK
             * 4 - Ajouter le nom de l'image à l'objet en cours (setImage) OK
             */
            
            // 1 - Récupérer l'image (dans une variable avec request)
            $image = $form->get('image')->getData();

            // Si une image a été uploadée
            if ($image) {

                // 2 - Modifier le nom de l'image (nom unique)
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

                // Transforme le nom de l'image en slug pour l'URL (minuscule, sans accent, sans espace, etc.)
                $safeFilename = $slugger->slug($originalFilename);

                // Reconstruit le nom de l'image avec un identifiant unique et son extension
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // 3 - Enregistrer l'image dans son répertoire ('articles_images')
                try {
                    $image->move(
                        $this->getParameter('articles_images'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    
                }

                // 4 - Ajouter le nom de l'image à l'objet en cours (setImage)
                $destination->setImage($newFilename);
            }

            $entityManager->persist($destination);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_destination_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_destination/new.html.twig', [
            'destination' => $destination,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_destination_show', methods: ['GET'])]
    public function show(Destination $destination): Response
    {
        return $this->render('admin_destination/show.html.twig', [
            'destination' => $destination,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_destination_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Destination $destination, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $form = $this->createForm(DestinationType::class, $destination);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             /**
             * TODO: Upload image
             * 1 - Récupérer l'image (dans une variable avec request) OK
             * 2 - Modifier le nom de l'image (nom unique)
             * 3 - Enregistrer l'image dans son répertoire ('articles_images') OK
             * 4 - Ajouter le nom de l'image à l'objet en cours (setImage) OK
             */
            
            // 1 - Récupérer l'image (dans une variable avec request)
            $image = $form->get('image')->getData();

            // Si une image a été uploadée
            if ($image) {

                // 2 - Modifier le nom de l'image (nom unique)
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

                // Transforme le nom de l'image en slug pour l'URL (minuscule, sans accent, sans espace, etc.)
                $safeFilename = $slugger->slug($originalFilename);

                // Reconstruit le nom de l'image avec un identifiant unique et son extension
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // 3 - Enregistrer l'image dans son répertoire ('articles_images')
                try {
                    $image->move(
                        $this->getParameter('articles_images'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    
                }

                // 4 - Ajouter le nom de l'image à l'objet en cours (setImage)
                $destination->setImage($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_admin_destination_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_destination/edit.html.twig', [
            'destination' => $destination,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_destination_delete', methods: ['POST'])]
    public function delete(Request $request, Destination $destination, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$destination->getId(), $request->request->get('_token'))) {
            $entityManager->remove($destination);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_destination_index', [], Response::HTTP_SEE_OTHER);
    }
}
