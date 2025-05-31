<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class ContactController extends AbstractController
{
    #[Route('/contact/submit', name: 'contact_submit', methods: ['POST'])]
    public function submit(Request $request): Response
    {
        // Fetch form data:
        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $message = $request->request->get('message');
        // TODO: Add form validation, send email, save contact message, etc.
        // Add flash message for success or redirect somewhere
        $this->addFlash('success', 'Thank you for your message. We will get back to you soon.');
        return $this->redirectToRoute('index'); // Redirect back to homepage or any page
    }
}
