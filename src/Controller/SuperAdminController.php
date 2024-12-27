<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SuperAdminController extends AbstractController
{
    #[Route('/super/admin', name: 'app_super_admin')]
    public function account()
    {
        // Vérifie que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        return $this->render('super_admin/index.html.twig', [
            'user' => $this->getUser(), // Récupère l'utilisateur connecté
        ]);
    }
}
