<?php

namespace App\Controller;

use App\Cart;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Payment;
use App\Repository\PaymentRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/checkout')]
class CheckoutController extends AbstractController
{
    private StripeClient $stripe;

    public function __construct(
        #[Autowire('%env(STRIPE_SECRET_KEY)%')] string $stripeKey, 
        private EntityManagerInterface $entityManager,
        private ProductRepository $productRepository
    ) {
        $this->stripe = new StripeClient($stripeKey);
    }

    #[Route('', name: 'checkout', methods: ["POST"])]
    public function checkout(Request $request): Response
    {
        $cartItems = Cart::getFullCartItemsFromCookie($request, $this->productRepository);
        $lineItems = $this->getLineItemsFromCartItems($cartItems);
        $totalPrice = array_reduce($lineItems, fn($carry, $item) => $carry + $item['quantity'] * $item['price_data']['unit_amount'], 0);
        $customer = $this->getUser()->getCustomer();

        $checkoutCustomer = $this->stripe->customers->create([
            'name' => $customer->getFirstName() . ' ' . $customer->getLastName()
        ]);
        $checkout_session = $this->stripe->checkout->sessions->create([
            'customer' => $checkoutCustomer,
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $request->getSchemeAndHttpHost() . $this->generateUrl('checkout_success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $request->getSchemeAndHttpHost() . $this->generateUrl('checkout_failure', ['message' => 'Stripe error, transaction failed!']),
        ]);

        $payment = $this->createPayment([
            'amount' => $totalPrice,
            'status' => 'pending',
            'type' => 'cc',
            'session_id' => $checkout_session->id
        ]);
        $order = $this->createOrderAndLinkItToPayment([
            'total_price' => $totalPrice,
            'status' => 'unpaid',
            'cart_items' => $cartItems
        ], $payment);
        $this->entityManager->flush();

        $response = new RedirectResponse($checkout_session->url);
        $response->headers->clearCookie('cart_items');
        return $response;
    }

    private function getLineItemsFromCartItems(array $cartItems): array
    {
        $lineItems = [];
        foreach ($cartItems as $cartItem) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $cartItem['title']
                    ],
                    'unit_amount' => $cartItem['price'] * 100,
                ],
                'quantity' => $cartItem['quantity'],
            ];
        }

        return $lineItems;
    }

    private function createOrderAndLinkItToPayment(array $data, Payment &$payment): Order
    {
        $order = new Order();
        $order->setTotalPrice($data['total_price'])
            ->setStatus($data['status'])
            ->setPayment($payment)
        ;
        foreach ($data['cart_items'] as $cartItem) {
            $orderItem = $this->createOrderItem([
                'order' => $order,
                'product' => $this->productRepository->find($cartItem['id']),
                'quantity' => $cartItem['quantity'],
                'unit_price' => $cartItem['price']
            ]);
            $order->addOrderItem($orderItem);
        }
        $payment->setRelatedOrder($order);

        $this->entityManager->persist($order);
        $this->entityManager->persist($payment);
        return $order;
    }

    private function createOrderItem(array $data): OrderItem
    {
        $orderItem = new OrderItem();
        $orderItem->setRelatedOrder($data['order'])
            ->setProduct($data['product'])
            ->setQuantity($data['quantity'])
            ->setUnitPrice($data['unit_price'])
        ;
        
        $this->entityManager->persist($orderItem);
        return $orderItem;
    }

    private function createPayment(array $data): Payment
    {
        $payment = new Payment();
        $payment->setAmount($data['amount'])
            ->setStatus($data['status'])
            ->setType($data['type'])
            ->setSessionId($data['session_id'])
        ;
        
        $this->entityManager->persist($payment);
        return $payment;
    }

    #[Route('/success', name: 'checkout_success')]
    public function success(Request $request, PaymentRepository $paymentRepository): Response
    {
        try {
            $session = $this->stripe->checkout->sessions->retrieve($request->query->get('session_id'));
            if (!$session) {
                throw new \Exception('Invalid Session ID!');
            }
            $payment = $paymentRepository->findOneBy(['sessionId' => $session->id, 'status' => 'pending']);
            if (!$payment) {
                throw new \Exception('Payment does not exist!');
            }
            $this->setPaymentAndOrderStatusAsPaid($payment, $payment->getRelatedOrder());

            $customer = $this->stripe->customers->retrieve($session->customer);
            return $this->render('checkout/success.html.twig', compact('customer'));
        } catch (\Exception $e) {
            return $this->redirectToRoute('checkout_failure', ['message' => $e->getMessage()]);
        }
    }

    private function setPaymentAndOrderStatusAsPaid(Payment $payment, Order $order): void
    {
        $payment->setStatus('paid');
        $order->setStatus('paid');

        $this->entityManager->persist($payment);
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    #[Route('/failure/{message}', name: 'checkout_failure')]
    public function failure(string $message): Response
    {
        return $this->render('checkout/failure.html.twig', [
            'message' => $message
        ]);
    }
}
