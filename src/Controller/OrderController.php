<?php

namespace App\Controller;

use App\Entity\Basket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order/{id}', name: 'order_details')]
    public function detail(Basket $basket): Response
    {
        // Vérifie que la commande appartient à l'utilisateur connecté
        if ($basket->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas accéder à cette commande.');
        }

        return $this->render('order/index.html.twig', [
            'order' => $basket,
        ]);
    }
}
