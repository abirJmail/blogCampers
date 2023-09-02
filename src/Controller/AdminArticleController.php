<?php

namespace App\Controller;
use DateTimeImmutable;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin/article')]
class AdminArticleController extends AbstractController
{
    #[Route('/', name: 'app_admin_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('admin_article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }
    


    #[Route('/new', name: 'app_admin_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $article = new Article();
        $article->setCreatedAt(new DateTimeImmutable());
        $form = $this->createForm(ArticleType::class, $article);
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
                $article->setImage($newFilename);
            }



            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_admin_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        $images = $article-> getImages();
        return $this->render('admin_article/show.html.twig', [
            'article' => $article,
            'images'=>$images
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
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
                $article->setImage($newFilename);
            }




            $entityManager->flush();

            return $this->redirectToRoute('app_admin_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
