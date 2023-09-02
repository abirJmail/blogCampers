<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin/category')]
class AdminCategoryController extends AbstractController
{
    #[Route('/', name: 'app_admin_category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin_category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
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
                $category->setImage($newFilename);
            }

            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->render('admin_category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
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
                $category->setImage($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_admin_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
