<?php

namespace App\Controller;

use App\Cart;
use App\Entity\Order;
use App\Entity\Payment;
use App\Entity\Product;
use App\Repository\OrderRepository;
use App\Repository\PaymentRepository;
use App\Stripe;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/checkout')]
#[IsGranted("ROLE_USER")]
class CheckoutController extends AbstractController
{
    public function __construct(
        private Stripe $stripe,
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('', name: 'checkout', methods: ["POST"])]
    public function checkout(Request $request, OrderRepository $orderRepository, PaymentRepository $paymentRepository): Response
    {
        $cartItems = Cart::getFullCartItemsFromCookie($request, $this->entityManager->getRepository(Product::class));
        $totalPrice = $this->stripe->getTotal($cartItems);
        $customer = $this->getUser()->getCustomer();
        $successUrl = $request->getSchemeAndHttpHost() . $this->generateUrl('checkout_success');
        $cancelUrl = $request->getSchemeAndHttpHost() . $this->generateUrl('checkout_failure', ['message' => 'Stripe error, transaction failed!']);
        $checkout_session = $this->stripe->createSession($customer, $cartItems, $successUrl, $cancelUrl);

        $payment = $paymentRepository->createPayment([
            'amount' => $totalPrice,
            'status' => 'pending',
            'type' => 'cc',
            'session_id' => $checkout_session->id
        ]);
        $orderRepository->createOrderAndLinkItToPayment([
            'total_price' => $totalPrice,
            'status' => 'unpaid',
            'products' => $cartItems
        ], $payment);
        $this->entityManager->flush();

        $response = new RedirectResponse($checkout_session->url);
        $response->headers->clearCookie('cart_items');
        return $response;
    }

    #[Route('/success', name: 'checkout_success')]
    public function success(Request $request, PaymentRepository $paymentRepository): Response
    {
        try {
            $session = $this->stripe->retreiveSession($request->query->getString('session_id'));
            if (!$session) {
                throw new \Exception('Invalid Session ID!');
            }
            $payment = $paymentRepository->findOneBy(['sessionId' => $session->id, 'status' => 'pending']);
            if (!$payment) {
                throw new \Exception('Payment does not exist!');
            }
            $this->setPaymentAndOrderStatusAsPaid($payment, $payment->getRelatedOrder());

            $customer = $this->stripe->retreiveCustomer($session);
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
