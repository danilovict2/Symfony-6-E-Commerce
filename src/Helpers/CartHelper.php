<?php

namespace App\Helpers;

use App\Entity\CartItem;
use App\Repository\CartItemRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

class CartHelper
{
    public function __construct(
        private Security $security,
        private CartItemRepository $cartItemRepository,
        private ProductRepository $productRepository
    ) {
    }

    public function getItemsCount(Request $request): int
    {
        $user = $this->security->getUser();
        return $user ?
            $this->cartItemRepository->getUserCartItemsCount($user) :
            array_reduce(
                $this->getCookieItems($request),
                fn ($carry, $item) => $carry + $item['quantity'],
                0
            );
    }

    public function getItems(Request $request): array
    {
        $user = $this->security->getUser();
        return $user ? $this->cartItemRepository->getUserCartItems($user) : $this->getCookieItems($request);
    }

    public function saveItems(Request $request): void
    {
        $cartItems = $this->getCookieItems($request);
        $user = $this->security->getUser();
        $dbCartItems = $this->cartItemRepository->getUserCartItemsGroupedByProduct($user);

        foreach ($cartItems as $cartItem) {
            if (isset($dbCartItems[$cartItem['product_id']])) {
                continue;
            }

            $newCartItem = $this->createCartItemEntity($cartItem);
            $this->cartItemRepository->save($newCartItem, true);
        }
    }

    private function createCartItemEntity($cartItem): CartItem
    {
        $newCartItem = new CartItem();
        $newCartItem->setCreator($this->security->getUser());
        $newCartItem->setProduct($this->productRepository->find($cartItem['product_id']));
        $newCartItem->setQuantity($cartItem['quantity']);

        return $newCartItem;
    }

    private function getCookieItems(Request $request): array
    {
        return $request->cookies->all('cart_items');
    }
}
