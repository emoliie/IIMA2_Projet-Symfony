<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\BasketContent;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
{
    // gère l'ajout d'un produit au panier
    #[Route('/basket/add/{id}', name: 'app_basket_add')]
    public function addToBasket(Product $product, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour ajouter un produit au panier.');
            return $this->redirectToRoute('app_login');
        }

        // Récupérer le panier actif de l'utilisateur
        $basket = $entityManager->getRepository(Basket::class)->findOneBy([
            'user' => $user,
            'status' => false
        ]);

        // Créer un nouveau panier s'il n'existe pas
        if (!$basket) {
            $basket = new Basket();
            $basket->setUser($user);
            $basket->setDate(new \DateTime());
            $basket->setStatus(false);

            $entityManager->persist($basket);
            $entityManager->flush();
        }

        // Vérifier si le produit est déjà dans le panier
        $basketContent = $entityManager->getRepository(BasketContent::class)->findOneBy([
            'basket' => $basket,
            'product' => $product,
        ]);

        if ($basketContent) {
            $basketContent->setQuantity($basketContent->getQuantity() + 1);
        } else {
            $basketContent = new BasketContent();
            $basketContent->setBasket($basket);
            $basketContent->setProduct($product);
            $basketContent->setQuantity(1);
            $basketContent->setDate(new \DateTime());

            $entityManager->persist($basketContent);
        }

        $entityManager->flush();

        $this->addFlash('success', 'Produit ajouté au panier avec succès.');

        return $this->redirectToRoute('app_basket');
    }

    // affiche les produits du panier

    #[Route('/basket', name: 'app_basket')]
    public function viewBasket(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à votre panier.');
            return $this->redirectToRoute('app_login');
        }

        $basket = $entityManager->getRepository(Basket::class)->findOneBy([
            'user' => $user,
            'status' => false
        ]);

        return $this->render('basket/index.html.twig', [
            'basket' => $basket,
        ]);
    }

    // supprime un produit du panier
    #[Route('/basket/remove/{id}', name: 'app_basket_remove')]
    public function removeFromBasket(BasketContent $basketContent, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($basketContent);
        $entityManager->flush();

        $this->addFlash('success', 'Produit retiré du panier avec succès.');

        return $this->redirectToRoute('app_basket');
    }

    #[Route('/basket/decrement/{id}', name: 'app_basket_decrement')]
    public function decrementQuantity(BasketContent $basketContent, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si la quantité est supérieure à 1
        if ($basketContent->getQuantity() > 1) {
            $basketContent->setQuantity($basketContent->getQuantity() - 1);
            $entityManager->flush();

            $this->addFlash('success', 'Une unité a été retirée du panier.');
        } else {
            // Si la quantité est 1, supprimer complètement le produit
            $entityManager->remove($basketContent);
            $entityManager->flush();

            $this->addFlash('success', 'Le produit a été retiré du panier.');
        }

        return $this->redirectToRoute('app_basket');
    }
}
