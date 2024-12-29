<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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

        // Données pour le super administrateur (si applicable)
        $unpaidBaskets = [];
        $usersToday = [];

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            // Récupère les paniers non achetés
            $unpaidBaskets = $entityManager->getRepository(Basket::class)->findBy([
                'status' => false,
            ]);

            // Récupère les utilisateurs inscrits aujourd'hui
            $today = new \DateTime('today');
            $usersToday = $entityManager->getRepository(User::class)->createQueryBuilder('u')
                ->where('u.createdAt >= :today')
                ->setParameter('today', $today)
                ->orderBy('u.createdAt', 'DESC')
                ->getQuery()
                ->getResult();
        }

        return $this->render('account/index.html.twig', [
            'user' => $user,
            'orders' => $orders, // Commandes de l'utilisateur
            'unpaidBaskets' => $unpaidBaskets, // Paniers non achetés (pour le super admin)
            'usersToday' => $usersToday, // Utilisateurs inscrits aujourd'hui (pour le super admin)
        ]);
    }
}