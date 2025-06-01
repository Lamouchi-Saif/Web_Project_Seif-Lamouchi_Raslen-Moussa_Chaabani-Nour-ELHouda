<?php

namespace App\Twig;

use App\Repository\CartItemRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class CartExtension extends AbstractExtension implements GlobalsInterface
{
    private CartItemRepository $cartItemRepository;
    private TokenStorageInterface $tokenStorage;

    public function __construct(CartItemRepository $cartItemRepository, TokenStorageInterface $tokenStorage)
    {
        $this->cartItemRepository = $cartItemRepository;
        $this->tokenStorage = $tokenStorage;
    }

    public function getGlobals(): array
    {
        $token = $this->tokenStorage->getToken();

        if (!$token || !is_object($user = $token->getUser())) {
            return [
                'cart_items' => [],
                'cart_total' => 0,
            ];
        }

        $cartItems = $this->cartItemRepository->findBy(['user' => $user]);
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->getProduct()->getPrice() * $item->getQuantity();
        }

        return [
            'cart_items' => $cartItems,
            'cart_total' => $total,
        ];
    }
}
