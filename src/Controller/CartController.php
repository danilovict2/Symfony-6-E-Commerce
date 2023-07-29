<?php

namespace App\Controller;

use App\Cart;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('', name: 'cart')]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig');
    }

    #[Route('/add/{title}', 'cart_add', methods: ['POST'])]
    public function add(string $title, ProductRepository $productRepository, Request $request): JsonResponse
    {
        $product = $productRepository->findOneByTitle($title);
        $quantity = $request->query->getInt('quantity');
        $cartItems = json_decode($request->cookies->get('cart_items'), true) ?? [];

        Cart::findOrCreateItemByProductAndUpdateQuantity($cartItems, $product, $quantity);
        $response = new JsonResponse(['count' => Cart::getItemCount($request)]);
        Cart::saveItemsToCookie($cartItems, $response);
        return $response;
    }
}
