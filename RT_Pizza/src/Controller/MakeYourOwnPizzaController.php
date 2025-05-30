<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MakeYourOwnPizzaController extends AbstractController
{
    #[Route('/make/your/own/pizza', name: 'make_your_own')]
    public function index(): Response
    {
        return $this->render('make_your_own_pizza/index.html.twig', [
            'controller_name' => 'MakeYourOwnPizzaController',
        ]);
    }
}
