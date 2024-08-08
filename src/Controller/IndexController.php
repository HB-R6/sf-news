<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function home(CategoryRepository $categoryRepository): Response
    {
        // 1 - Je requête le modèle (SQL/BDD)
        // pour récupérer les catégories
        $categories = $categoryRepository->findAll();

        // 2 - Je demande à Twig de rendre une vue
        // et je lui passe les catégories
        // Répertoire racine des vues : templates/
        return $this->render('index/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/about', name: 'about_page')]
    public function about(): Response
    {
        return $this->render('index/about.html.twig');
    }
}
