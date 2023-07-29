<?php

namespace App\Controller;

use App\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('', name: 'cart')]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $cookieCartItems = json_decode($request->cookies->get('cart_items'), true) ?? [];
        $cartItems = array_map(
            function ($cookieCartItem) use ($productRepository) {
                $product = $productRepository->find($cookieCartItem['product_id']);
                return [
                    'id' => $product->getId(),
                    'image' => $product->getImage(),
                    'title' => $product->getTitle(),
                    'price' => $product->getPrice(),
                    'quantity' => $cookieCartItem['quantity'],
                ];
            },
            $cookieCartItems
        );

        return $this->render('cart/index.html.twig', [
            'cartItems' => $cartItems
        ]);
    }

    #[Route('/add/{title}', name: 'cart_add', methods: ['POST'])]
    public function add(string $title, ProductRepository $productRepository, Request $request): JsonResponse
    {
        $product = $productRepository->findOneByTitle($title);
        $quantity = $request->query->getInt('quantity');
        $cartItems = json_decode($request->cookies->get('cart_items'), true) ?? [];

        Cart::findOrCreateItemByProductAndUpdateQuantity($cartItems, $product, $quantity);
        $response = new JsonResponse(['count' => Cart::getItemCount($cartItems)]);
        Cart::saveItemsToCookie($cartItems, $response);
        return $response;
    }

    #[Route('/remove/{title}', name: 'cart_remove', methods: ['POST'])]
    public function remove(string $title, Request $request, ProductRepository $productRepository): JsonResponse
    {
        $product = $productRepository->findOneByTitle($title);
        $cartItems = json_decode($request->cookies->get('cart_items'), true) ?? [];
        foreach ($cartItems as $i => &$item) {
            if ($item['product_id'] === $product->getId()) {
                array_splice($cartItems, $i, 1);
                break;
            }
        }

        $response = new JsonResponse(['count' => Cart::getItemCount($cartItems)]);
        Cart::saveItemsToCookie($cartItems, $response);
        return $response;
    }

    #[Route('/items/count', name: 'cart_items_count', methods: ['POST'])]
    public function itemCount(Request $request)
    {
        $cartItems = json_decode($request->cookies->get('cart_items'), true) ?? [];
        return $this->json([
            'count' => Cart::getItemCount($cartItems)
        ]);
    }
}
