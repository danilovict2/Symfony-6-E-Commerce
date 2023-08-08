<?php

namespace App\Controller;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_USER")]
class OrderController extends AbstractController
{
    #[Route('/orders', name: 'orders')]
    public function index(): Response
    {
        $orders = $this->getUser()->getOrders();
        return $this->render('order/index.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/order/{id}', name: 'order_show')]
    public function show(Order $order): Response
    {
        if ($order->getCreatedBy() !== $this->getUser()) {
            return new Response("You don't have permission to view this order!", 403);
        }

        return $this->render('order/show.html.twig',[
            'order' => $order
        ]);
    }
}
