<?php

namespace App\Twig;

use App\Form\ContactFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class GlobalContactFormExtension extends AbstractExtension implements GlobalsInterface
{
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function getGlobals(): array
    {
        // Create a form view you can access in any template
        $form = $this->formFactory->create(ContactFormType::class);

        return [
            'globalContactForm' => $form->createView()
        ];
    }
}
