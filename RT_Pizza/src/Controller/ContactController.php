<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Form\ContactFormType;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

final class ContactController extends AbstractController
{
    #[Route('/contact/send', name: 'contact_send', methods: ['POST'])]
    public function send(Request $request, MailerInterface $mailer, LoggerInterface $logger)
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = (new Email())
                ->from('rtpizza901@gmail.com')
                ->replyTo($data['email'])
                ->to('s.lamouchi23@gmail.com')
                ->subject('Contact Message from RT Pizza')
                ->text(
                    "From: {$data['name']}\nEmail: {$data['email']}\n\nMessage:\n{$data['message']}"
                );

            try {
                $mailer->send($email);
                $this->addFlash('success', 'Message sent successfully!');
            } catch (TransportExceptionInterface $e) {
                $this->addFlash('error', 'Failed to send email: ' . $e->getMessage());
                $this->addFlash('error', 'Mailer error: ' . $e->getMessage());
                $logger->error('Mailer failed: ' . $e->getMessage());
            }
        } else {
            $this->addFlash('error', 'Invalid form submission.');
        }

        return $this->redirect($request->headers->get('referer') ?? $this->generateUrl('index'));
    }
}
