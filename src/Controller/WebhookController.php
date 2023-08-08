<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Payment;
use App\Repository\PaymentRepository;
use App\Stripe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebhookController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/webhook/stripe', name: 'stripe_webhook', methods: ["POST"])]
    public function webhook(
        #[Autowire("%env(STRIPE_WEBHOOK_SECRET)%")] string $endpoint,
        Stripe $stripe,
        Request $request,
        PaymentRepository $paymentRepository
    ): Response {
        $payload = @file_get_contents('php://input');
        $sig_header = $request->server->get('HTTP_STRIPE_SIGNATURE');
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return new Response('', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return new Response('', 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $paymentIntent = $event->data->object;
                $sessionId = $paymentIntent['id'];

                $payment = $paymentRepository->findOneBy(['sessionId' => $sessionId, 'status' => 'pending']);
                if ($payment) {
                    $this->setPaymentAndOrderStatusAsPaid($payment, $payment->getRelatedOrder());
                }
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return new Response('');
    }

    private function setPaymentAndOrderStatusAsPaid(Payment $payment, Order $order): void
    {
        $payment->setStatus('paid');
        $order->setStatus('paid');

        $this->entityManager->persist($payment);
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }
}
