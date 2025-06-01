<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Ingredient;

final class MakeYourOwnPizzaController extends AbstractController
{
    // src/Controller/PizzaController.php
    #[Route('/create-pizza', name: 'create_pizza')]
    public function create(EntityManagerInterface $em): Response {
        $ingredients = $em->getRepository(Ingredient::class)->findAll();
        return $this->render('pizza/create.html.twig', [
            'ingredients' => $ingredients
        ]);
    }

}
