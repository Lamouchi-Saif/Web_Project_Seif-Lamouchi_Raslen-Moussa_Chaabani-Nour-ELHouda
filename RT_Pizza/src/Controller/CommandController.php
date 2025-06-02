<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CartItem;
use App\Repository\CartItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Psr\Log\LoggerInterface;

class CommandController extends AbstractController
{
    private EntityManagerInterface $em;
    private Security $security;
    private UserPasswordHasherInterface $passwordHasher;
    private CartItemRepository $cartItemRepository;
    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $em,
        Security $security,
        UserPasswordHasherInterface $passwordHasher,
        CartItemRepository $cartItemRepository,
        LoggerInterface $logger
    ) {
        $this->em = $em;
        $this->security = $security;
        $this->passwordHasher = $passwordHasher;
        $this->cartItemRepository = $cartItemRepository;
        $this->logger = $logger;
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/command', name: 'command', methods: ['GET'])]
    public function index(): Response
    {
        $cartItems = $this->cartItemRepository->findBy(['user' => $this->getUser()]);

        // Afficher la page de commande
        return $this->render('command/index.html.twig', [
            'cartItems' => $cartItems,
        ]);
    }
    #[IsGranted('ROLE_USER')]
    #[Route('/submit-order', name: 'app_order_submit', methods: ['POST'])]
    public function submitOrder(Request $request): JsonResponse
    {
        try {
            /** @var User $user */
            $user =$this->getCurrentUser(); // Get the currently logged-in user
            // For existing user, require phone and address:
            if (!$user->getPhone()) {
                $phone = $request->request->get('phone');
                if (!$phone) {
                    return new JsonResponse(['error' => 'Phone number is required'], 400);
                }
                $user->setPhone($phone);
            }
            if (!$user->getAddress()) {
                $address = $request->request->get('address');
                if (!$address) {
                    return new JsonResponse(['error' => 'Address is required'], 400);
                }
                $user->setAddress($address);
            }


            $this->em->flush();

            // Continue as before with cart items and order creation
            $cartItems = $this->cartItemRepository->findBy(['user' => $user]);

            if (!$cartItems) {
                return new JsonResponse(['error' => 'Cart is empty'], 401);
            }

            foreach ($cartItems as $cartItem) {
                $commande = new Commande();
                $commande->setUser($user);
                $commande->setProduct($cartItem->getProduct());
                $commande->setQuantity($cartItem->getQuantity());
                $commande->setDate(new \DateTime());

                $this->em->persist($commande);
            }

            foreach ($cartItems as $cartItem) {
                $this->em->remove($cartItem);
            }

            $this->em->flush();
            return new JsonResponse(['success' => true, 'message' => 'Order submitted successfully']);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => 'Logging failed: ' . $e->getMessage()], 500);
        }
    }
    private function getCurrentUser()
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('User not logged in');
        }
        return $user;
    }
}
