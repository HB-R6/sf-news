<?php

namespace App\Controller;

use App\Entity\NewsletterEmail;
use App\Form\NewsletterType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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

    #[Route('/newsletter/subscribe', name: "newsletter_subscribe", methods: ["GET", "POST"])]
    public function newsletterSubscribe(
        Request $request,
        EntityManagerInterface $em,
        MailerInterface $mailer
    ): Response {
        $newsletter = new NewsletterEmail();
        $form = $this->createForm(NewsletterType::class, $newsletter);

        // Prend en charge la requête entrante
        // et s'il y a des données, les met dans $newsletter
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($newsletter);
            $em->flush();

            $email = (new Email())
            ->from('admin@hb-news.com')
            ->to($newsletter->getEmail())
            ->subject('HB NEWS - Inscription à la newsletter')
            ->text('Votre email a bien été enregistré à notre newsletter')
            ->html('<p>Votre email a bien été enregistré à notre newsletter</p>');

            $mailer->send($email);

            return $this->redirectToRoute('newsletter_confirm');
        }

        return $this->render('index/newsletter.html.twig', [
            'newsletterForm' => $form
        ]);
    }

    #[Route('/newsletter/thanks', name: "newsletter_confirm")]
    public function newsletterConfirm(): Response
    {
        return $this->render('index/newsletter_confirm.html.twig');
    }
}
