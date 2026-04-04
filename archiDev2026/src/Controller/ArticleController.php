<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
//Chemin du formulaire des articles
use App\Form\ArticleType;
use App\Entity\Article;
use App\Repository\ArticleRepository;

use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ArticleController extends AbstractController
{
    // private ArticleRepository $articleRepository;

    // public function __contruct(ArticleRepository $articleRepository){
    //     $this->articleRepository = $articleRepository;   
    // }


    #[Route('/article', name: 'app_article')]
    public function index(ArticleService $articleService): Response
    {
        //Récupérer l'ensemble des articles la méthode findAll()
        $articles = $articleService->articleAll();

        $articlesCount = $articleService->articleAllCount();
        $articlesPublishCount = $articleService->articleAllPublish();
        
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'articlesCount' => $articlesCount,
            'articlesPublishCount' => $articlesPublishCount
        ]);
    }

    #[isGranted('ROLE_USER')]
    #[Route('/article/new', name: 'app_article_new')]
    public function new( Request $request, EntityManagerInterface $em,
                        SluggerInterface $slugger,
                        #[Autowire('%kernel.project_dir%/public/uploads/files')] string $filesDirectory): Response
    {
        //Instance de l'objet
        $article = new Article();

        // Relation entre le formulaire et l'instance
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $file = $form->get('file')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                try {
                    $file->move($filesDirectory, $newFilename);
                } catch (FileException $e) {
                }
                $article->setFile($newFilename);
            }

            //Vérifier si le user est connecté
            $user = $this->getUser();
            if($user){
                $article->setAuthor($user);
            } 

            //Persiste les données dans l'objet et prépare la requête SQL
            $em->persist($article);
            //Permet d'enregistrer les données dans la BDD
            $em->flush();
            //Permet de revenir que la liste des articles
            return $this->redirectToRoute('app_article');
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Affiche un article
    /*
    * param id = id de l'article     
    */
    #[Route('/article/{id}', name: 'app_article_show')]
    public function show( Article $article ): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[isGranted('ROLE_USER')]
    #[Route('/article/{id}/edit', name: 'app_article_edit')]
    public function edit( Article $article, Request $request, EntityManagerInterface $em ): Response
    {
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('app_article');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/article/{id}/delete', name: 'app_article_delete')]
    public function delete( Article $article, EntityManagerInterface $em): Response
    {
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('app_article');
    }

}
