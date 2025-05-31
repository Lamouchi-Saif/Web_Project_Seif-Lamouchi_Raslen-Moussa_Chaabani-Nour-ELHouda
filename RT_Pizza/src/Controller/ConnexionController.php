<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\UserRepository;
use function Symfony\Component\Clock\now;

final class ConnexionController extends AbstractController
{
    #[Route('/login_reg_page', name: 'log_reg_page')]
    public function LoginRegPage(){
        return $this->render('connexion/index.html.twig',[
        ]);
    }
    
    #[Route('/register', name: 'register')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
    ): Response {
        $username = $request->request->get('username');
        $email = $request->request->get('email');
        $pass = $request->request->get('password');

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $hashedPass = $hasher->hashPassword($user,$pass);
        $user->setPasswordHash($hashedPass);
        $user->setIsAdmin(false);
        $user->setCreatedAt(now());
        $user->setUpdatedAt(now());
        $em->persist($user);
        $em->flush();
        $this->addFlash('success','Account successfully created!');
        return $this->render('connexion/index.html.twig');
    }
    #[Route('/login', name: 'login')]
    public function login(
        AuthenticationUtils $authenticationUtils    
    ) : Response{
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('connexion/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);  
    }
}

