<?php

namespace App\Controller;

use App\Repository\IngredientRepository;
use App\Entity\IngredientStock;
use App\Entity\Ingredient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Form\IngredientTypeForm;

class IngredientStockController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/ingredient_stock', name: 'ingredient_stock')]
    public function index(
        IngredientRepository $ingredientRepository,
        Request $request
    ): Response {
        // Get sorting parameters from request
        $sortBy = $request->query->get('sort_by', 'name'); // Default sort by name
        $sortDirection = $request->query->get('sort_direction', 'asc'); // Default ascending

        // Validate sort direction
        $sortDirection = in_array(strtolower($sortDirection), ['asc', 'desc']) ? $sortDirection : 'asc';

        // Get sorted ingredients
        $ingredients = $ingredientRepository->findAllSorted($sortBy, $sortDirection);

        return $this->render('ingredient_stock/index.html.twig', [
            'ingredients' => $ingredients,
            'current_sort' => $sortBy,
            'current_direction' => $sortDirection
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/ingredient_stock/add', name: 'ingredient_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientTypeForm::class, $ingredient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Create and set the IngredientStock
            $ingredientStock = new IngredientStock();
            $ingredientStock->setQuantity($form->get('quantity')->getData());
            $ingredientStock->setUnit($form->get('unit')->getData());
            $ingredientStock->setPrice($form->get('price')->getData());

            $ingredient->setIngredientStock($ingredientStock);
            $ingredientStock->setIngredient($ingredient);

            $entityManager->persist($ingredient);
            $entityManager->flush();

            $this->addFlash('success', 'Ingredient added successfully!');
            return $this->redirectToRoute('ingredient_stock');
        }

        return $this->render('ingredient_stock/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/ingredient_stock/edit/{id}', name: 'ingredient_edit', methods: ['POST'])]
    public function edit(
        Request $request,
        Ingredient $ingredient,
        EntityManagerInterface $entityManager
    ): Response {
        // Get form data
        $formData = $request->request->all();
        // Debug output for form data

        // $output = '';
        // foreach ($formData as $key => $value) {
        //     $output .= 'Key: ' . htmlspecialchars($key) . ', Value: ' . htmlspecialchars($value) . '<br>';
        // }
        // return new Response($output);

        // Validate and set values
        if (empty($formData['name'])) {
            $this->addFlash('error', 'Ingredient name cannot be empty');
            return $this->redirectToRoute('ingredient_stock', ['editing' => $ingredient->getId()]);
        }

        try {
            $ingredient->setName($formData['name']);
            $ingredient->setType($formData['type'] ?? 'other');

            $ingredientStock = $ingredient->getIngredientStock();
            $ingredientStock->setQuantity((float) ($formData['quantity'] ?? 0));
            $ingredientStock->setUnit($formData['unit'] ?? 'grams');
            $ingredientStock->setPrice((float) ($formData['price'] ?? 0));

            $entityManager->flush();

            $this->addFlash('success', 'Ingredient updated successfully!');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error updating ingredient: ' . $e->getMessage());
        }

        return $this->redirectToRoute('ingredient_stock');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/ingredient_stock/delete/{id}', name: 'ingredient_delete')]
    public function delete(int $id, IngredientRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $ingredient = $repository->find($id);
        if ($ingredient) {
            $entityManager->remove($ingredient);
            $entityManager->flush();
            $this->addFlash('success', 'Ingredient deleted successfully!');
            return $this->redirectToRoute('ingredient_stock');
        }
        $this->addFlash('error', 'Ingredient not found!');
        return $this->redirectToRoute('ingredient_stock');
    }
}
