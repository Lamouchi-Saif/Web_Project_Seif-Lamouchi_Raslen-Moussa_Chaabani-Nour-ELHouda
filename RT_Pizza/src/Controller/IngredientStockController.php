<?php

namespace App\Controller;

use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientStockController extends AbstractController
{
    #[Route('/ingredients', name: 'ingredient_stock')]
    public function index(IngredientRepository $ingredientRepository): Response
    {
        // Check if the user is an admin
        $isAdmin = $this->isGranted('ROLE_ADMIN');
        if (!$isAdmin) {
            // If not an admin, redirect to the homepage or show an error
            return $this->redirectToRoute('index');
        }

        // Fetch all ingredients
        $ingredients = $ingredientRepository->findAll();

        return $this->render('ingredients/index.html.twig', [
            'ingredients' => $ingredients,
            'isAdmin' => $isAdmin, // Pass the admin status to the template
        ]);
    }

    #[Route('/ingredients/add', name: 'ingredient_add')]
    public function add(): Response
    {
        // Logic to add a new ingredient to stock (form handling will be added later)
        return $this->render('ingredients/add.html.twig');
    }
}

