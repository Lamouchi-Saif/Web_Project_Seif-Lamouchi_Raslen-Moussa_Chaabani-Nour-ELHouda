<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    #[Route('/menu', name: 'menu')]
    public function index(ProductRepository $productRepository): Response
    {
        // Fetch all products
        $products = $productRepository->findAll();
        

        return $this->render('menu/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/menu/add', name: 'menu_add')]
    public function add(): Response
    {
        if($this->isGranted('ROLE_ADMIN')) {
            // Logic to add a new product (form handling will be added later)
            return $this->render('menu/add.html.twig');
        }else{
            // If the user is not an admin, redirect to the homepage or show an error
            return $this->redirectToRoute('index');
        }
    }

    #[Route('/menu/edit/{id}', name: 'menu_edit')]
    public function edit(int $id): Response
    {
        if($this->isGranted('ROLE_ADMIN')) {
            // Logic to edit a product (form handling will be added later)
            return $this->render('menu/edit.html.twig', [
                'id' => $id
            ]);
        } else {
            // If the user is not an admin, redirect to the homepage or show an error
            return $this->redirectToRoute('index');
        }
    }
}
