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
        return $this->render('order/show.html.twig');
    }
}
