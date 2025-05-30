<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class ConnexionController extends AbstractController
{
    #[Route('/connexion', name: 'connexion')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
        AuthenticationUtils $authenticationUtils
    ): Response {
        // Get login error and last email typed
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastEmail = $authenticationUtils->getLastUsername();

        // Build and handle the registration form
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $hasher->hashPassword($user, $form->get('plainPassword')->getData());
            $user->setPasswordHash($hashedPassword);
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setUpdatedAt(new \DateTimeImmutable());
            $user->setIsAdmin(false);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Registration successful. Please login.');

            return $this->redirectToRoute('connexion');
        }

        return $this->render('connexion/index.html.twig', [
            'registrationForm' => $form->createView(),
            'last_email' => $lastEmail,
            'error' => $error,
        ]);
    }
}

