<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_app')]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('app/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }
}
