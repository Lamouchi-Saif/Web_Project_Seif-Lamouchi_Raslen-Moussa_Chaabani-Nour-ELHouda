<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Image as ImageConstraint;


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

    #[Route('/menu/add_page', name: 'menu_add_page')]
    public function add_page(Request $request, SluggerInterface $sluggerInterface): Response
    {
        if($this->isGranted('ROLE_ADMIN')) {    
            return $this->render('menu/add.html.twig');
        }else{
            // If the user is not an admin, redirect to the homepage or show an error
            return $this->redirectToRoute('index');
        }
    }
    #[Route('/add',name: 'menu_add')]
    public function add(Request $request, SluggerInterface $sluggerInterface,EntityManagerInterface $entityManagerInterface): Response
    {
        if($this->isGranted('ROLE_ADMIN')){
            $image = $request->files->get('image');
            $name = $request->request->get('name');
            //XSS PROTECTION ---
            $mimeType = $image->getMimeType();
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            if (!in_array($mimeType, $allowedMimeTypes)) {
                $this->addFlash('error', 'Invalid image format.');
                return $this->redirectToRoute('menu_add_page');
            }
            $product = new Product();
            $product->setName($name);
            $description = $request->request->get('description');
            $product->setDescription($description);
            $price = $request->request->get('price');
            $product->setPrice($price);
            if ($image) {
                //XSS PROTECTION IMAGE UPLOAD
                $validator = Validation::createValidator();
                $violations = $validator->validate(
                    $image,
                    new ImageConstraint([
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
                    ])
                );
                if (count($violations) > 0) {
                    $this->addFlash('error', 'Invalid image file.');
                    return $this->redirectToRoute('menu_add_page');
                }
                $extension = $image->guessExtension();
                $newName = uniqid('img_', true) . '.' . $extension;
                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newName
                    );
                    $product->setImageUrl('images/' . $newName);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Could not upload the image.');
                    return $this->redirectToRoute('menu_add_page');
                }
            }

            $entityManagerInterface->persist($product);
            $entityManagerInterface->flush();
            $this->addFlash('success','Addition Successful!');
            return $this->redirectToRoute('menu_add_page');
        } else {
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
