<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    // Affiche le produit
    #[Route('/product/{id}', name: 'product_show', methods: ['GET', 'POST'])]
    public function show(int $id, ProductRepository $productRepository, EntityManagerInterface $em, Request $request): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Le produit n\'existe pas.');
        }

        // Vérifie la suppression
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SUPER_ADMIN')) {
            if ($request->isMethod('POST') && $this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
                $em->remove($product);
                $em->flush();

                $this->addFlash('success', 'Produit supprimé avec succès.');
                return $this->redirectToRoute('app_app');
            }
        }

        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);
    }
}
