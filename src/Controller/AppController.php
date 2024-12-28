<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_app')]
    public function index(
        ProductRepository $productRepository,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $form = null;

        // Vérifie si l'utilisateur est admin ou super admin
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SUPER_ADMIN')) {
            $product = new Product();
            $form = $this->createForm(ProductFormType::class, $product);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $imageFile = $form->get('image')->getData();

                if ($imageFile) {
                    $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                    $imageFile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );

                    $product->setImage($newFilename);
                }

                $em->persist($product);
                $em->flush();

                $this->addFlash('success', 'Produit ajouté avec succès.');
                return $this->redirectToRoute('app_app');
            }
        }

        return $this->render('app/index.html.twig', [
            'products' => $productRepository->findAll(),
            'add_product' => $form ? $form->createView() : null,
        ]);
    }
}
