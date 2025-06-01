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
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Email;

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
        UserRepository $userRepository
    ): Response {
        $username = $request->request->get('username');
        $email = $request->request->get('email');
        $pass = $request->request->get('password1');
        $repPass = $request->request->get('password2');
        $existingUsername = $userRepository->findOneBy(['username' => $username]);
        $existingEmail = $userRepository->findOneBy(['email' => $email]);
        $registerError = false;
        $validator = Validation::createValidator();

        $violations = $validator->validate($email, [
            new Email(['message' => 'Please enter a valid email address.'])
        ]);

        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $this->addFlash('error', $violation->getMessage());
            }
            return $this->redirectToRoute('log_reg_page');
        }
        if($existingUsername){
            $registerError = true;
            $this->addFlash('error','user with this username exists.');
            return $this->redirectToRoute('log_reg_page',[
                'registerError' => $registerError
            ]);
        }
        if($existingEmail){
            $registerError = true;
            $this->addFlash('error','user with this email exists.');
            return $this->redirectToRoute('log_reg_page',[
                'registerError' => $registerError
            ]);
        }
        if($pass!=$repPass){
            $registerError = true;
            $this->addFlash('error','password mismatch detected.');
            return $this->redirectToRoute('log_reg_page',[
                'registerError' => $registerError
            ]);
        }
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
        return $this->render('connexion/index.html.twig',[
            'registerError' => $registerError
        ]);
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
    #[Route('/logout', name: 'logout')]
    public function logout(): void
    {
        
    }
}

