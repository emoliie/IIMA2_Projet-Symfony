<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {

        // Si l'utilisateur est déjà connecté, redirigez-le vers la page de compte
        if ($this->getUser()) {
            return $this->redirectToRoute('app_account');
        }

        $user = new User();

        // Création du formulaire d'inscription
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('password')->getData();
            // Hachage du mot de passe
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // Ajout du rôle par défaut
            $user->setRoles(['ROLE_USER']);

            // Sauvegarde de l'utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // return $security->login($user, AppAuthenticator::class, 'main');
            return $this->redirectToRoute('app_login');
        }

        // Affichage du formulaire d'inscription
        return $this->render('register/index.html.twig', [
            'form' => $form
        ]);
    }
}
