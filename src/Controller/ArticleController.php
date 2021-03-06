<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/admin/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('admin/article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="article_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setCreatedAt(new DateTime());
            // verification upload picture
            $picture = $form->get('picture')->getData();
            $newFilename = 'article' . '-' . uniqid() . '.' . $picture->guessExtension();
            // Move the file to the directory where brochures are stored
            if (is_string($this->getParameter('pictures_directory'))) {
                try {
                    $picture->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $error) {
                    // ... handle exception if something happens during file upload
                    return $this->render('errors/error500.html.twig');
                }
            }
            // instead of its contents
            $article->setPicture($newFilename);
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="article_show", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('admin/article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", requirements={"id"="\d+"}, methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUpdatedAt(new DateTime());

            if ($form->get('picture')->getData() != null) {
                // verification upload picture
                $picture = $form->get('picture')->getData();

                $newFilename = 'article' . '-' . uniqid() . '.' . $picture->guessExtension();
                // Move the file to the directory where brochures are stored
                if (is_string($this->getParameter('pictures_directory'))) {
                    try {
                        $picture->move(
                            $this->getParameter('pictures_directory'),
                            $newFilename
                        );
                    } catch (FileException $error) {
                        // ... handle exception if something happens during file upload
                        return $this->render('errors/error500.html.twig');
                    }
                }
                // instead of its contents
                $article->setPicture($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", requirements={"id"="\d+"}, methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
    }
}
