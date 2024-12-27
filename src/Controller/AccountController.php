<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Basket;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function account(EntityManagerInterface $entityManager): Response
    {
        // Vérifie que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        // Récupère les commandes finalisées de l'utilisateur
        $orders = $entityManager->getRepository(Basket::class)->findBy([
            'user' => $user,
            'status' => true, // Commandes finalisées
        ]);

        return $this->render('account/index.html.twig', [
            'user' => $user,
            'orders' => $orders, // Passe les commandes à la vue
        ]);
    }
}
