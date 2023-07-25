<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/product/{title}', name: 'product_show')]
    public function show(string $title, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneByTitle($title);
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }
}
