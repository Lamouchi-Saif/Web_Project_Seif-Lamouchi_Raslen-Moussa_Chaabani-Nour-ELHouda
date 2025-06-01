<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Repository\CartItemRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CartController extends AbstractController
{
    private EntityManagerInterface $em;
    private Security $security;
    private CartItemRepository $cartItemRepository;
    private ProductRepository $productRepository;

    public function __construct(
        EntityManagerInterface $em,
        Security $security,
        CartItemRepository $cartItemRepository,
        ProductRepository $productRepository
    ) {
        $this->em = $em;
        $this->security = $security;
        $this->cartItemRepository = $cartItemRepository;
        $this->productRepository = $productRepository;
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/cart/add/{productId}', name: 'cart_add', methods: ['POST'])]
    public function addToCart(int $productId): JsonResponse
    {
        $user = $this->getCurrentUser();

        $product = $this->productRepository->find($productId);
        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        $cartItem = $this->cartItemRepository->findOneBy([
            'user' => $user,
            'product' => $product,
        ]);

        if ($cartItem) {
            $cartItem->setQuantity($cartItem->getQuantity() + 1);
        } else {
            $cartItem = new CartItem();
            $cartItem->setUser($user);
            $cartItem->setProduct($product);
            $cartItem->setQuantity(1);
            $cartItem->setCreatedAt(new \DateTime());
            $this->em->persist($cartItem);
        }

        $this->em->flush();

        return new JsonResponse(['success' => true, 'quantity' => $cartItem->getQuantity()]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/cart/remove/{productId}', name: 'cart_remove', methods: ['POST'])]
    public function removeFromCart(int $productId): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not logged in'], Response::HTTP_UNAUTHORIZED);
        }

        $product = $this->productRepository->find($productId);
        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        $cartItem = $this->cartItemRepository->findOneBy([
            'user' => $user,
            'product' => $product,
        ]);

        if (!$cartItem) {
            return new JsonResponse(['error' => 'Item not in cart'], Response::HTTP_NOT_FOUND);
        }

        if ($cartItem->getQuantity() > 1) {
            $cartItem->setQuantity($cartItem->getQuantity() - 1);
        } else {
            $this->em->remove($cartItem);
        }

        $this->em->flush();

        return new JsonResponse(['success' => true]);
    }


    #[IsGranted('ROLE_USER')]
    #[Route('/cart/items', name: 'cart_items', methods: ['GET'])]
    public function getCartItems(): JsonResponse
    {
        $user = $this->getCurrentUser();

        $cartItems = $this->cartItemRepository->findBy(['user' => $user]);

        $data = [];
        foreach ($cartItems as $item) {
            $data[] = [
                'productId' => $item->getProduct()->getId(),
                'name' => $item->getProduct()->getName(),
                'price' => $item->getProduct()->getPrice(),
                'imageUrl' => '/'. $item->getProduct()->getImageUrl(),
                'quantity' => $item->getQuantity(),
            ];
        }

        return new JsonResponse($data);
    }
    #[IsGranted('ROLE_USER')]
#[Route('/cart', name: 'cart_show', methods: ['GET'])]
public function showCart(): Response
{
    $user = $this->getCurrentUser();

    $cartItems = $this->cartItemRepository->findBy(['user' => $user]);

    $total = 0;
    foreach ($cartItems as $item) {
        $total += $item->getProduct()->getPrice() * $item->getQuantity();
    }

    return $this->render('cart/show.html.twig', [
        'cart_items' => $cartItems,
        'cart_total' => $total,
    ]);
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
