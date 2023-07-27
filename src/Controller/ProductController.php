<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

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

    #[Route('/product/{product}/full', name: 'product_get')]
    public function getFullProduct(Product $product): JsonResponse
    {
        return $this->json(
            [
                'product' => $product
            ],
            context: [
                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => fn ($obj) => $obj->getId()
            ]
        );
    }
}
