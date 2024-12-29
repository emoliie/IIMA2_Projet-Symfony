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

    // Modifie le produit
    #[Route('/product/edit/{id}', name: 'product_edit', methods: ['GET', 'POST'])]
    public function edit(
        Product $product,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        // Vérifie que l'utilisateur a les droits nécessaires
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé. Vous n\'êtes pas autorisé à modifier ce produit.');
        }

        // Crée le formulaire pour modifier le produit
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gère l'upload d'une nouvelle image si elle est modifiée
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('upload_directory'),
                    $newFilename
                );
                $product->setImage($newFilename);
            }

            // Sauvegarde les modifications
            $em->flush();

            $this->addFlash('success', 'Produit modifié avec succès.');
            return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }
}
