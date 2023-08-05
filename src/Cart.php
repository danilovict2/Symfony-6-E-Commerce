<?php

namespace App;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class Cart
{
    public static function getItemCount(array $cartItems): int
    {
        return array_reduce(
            $cartItems,
            fn($carry, $item) => $carry + $item['quantity'],
            0
        );
    }

    public static function findOrCreateItemByProductAndUpdateQuantity(array &$cartItems, Product $product, int $quantity): void
    {
        foreach ($cartItems as &$item) {
            if ($item['product_id'] === $product->getId()) {
                $item['quantity'] += $quantity;
                return;
            }
        }
        self::createItem($cartItems, $product, $quantity);
    }

    private static function createItem(array &$cartItems, Product $product, int $quantity): void
    {
        $cartItems[] = [
            'product_id' => $product->getId(),
            'quantity' => $quantity,
            'price' => $product->getPrice()
        ];
    }

    public static function saveItemsToCookie(array $cartItems, Response $response): void
    {
        $cookie = new Cookie("cart_items", json_encode($cartItems), (new \DateTime('now'))->modify("+12 day"), "/");
        $response->headers->setCookie($cookie);
    }
}
