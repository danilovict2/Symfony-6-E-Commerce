<?php 

namespace App;

use App\Entity\Customer;
use Stripe\Checkout\Session;
use Stripe\Customer as StripeCustomer;
use Stripe\StripeClient;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class Stripe
{
    private StripeClient $stripe;

    public function __construct(#[Autowire('%env(STRIPE_SECRET_KEY)%')] string $stripeKey)
    {
        $this->stripe = new StripeClient($stripeKey);
    }

    public function createSession(Customer $customer, array $items, string $successUrl, string $cancelUrl): Session
    {
        $checkoutCustomer = $this->createCheckoutCustomerFromCustomerEntity($customer);
        $lineItems = $this->getLineItems($items);
        return $this->stripe->checkout->sessions->create([
            'customer' => $checkoutCustomer,
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $cancelUrl,
        ]);
    }

    private function getLineItems(array $items): array
    {
        $lineItems = [];
        foreach ($items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['title']
                    ],
                    'unit_amount' => $item['price'] * 100,
                ],
                'quantity' => $item['quantity'],
            ];
        }

        return $lineItems;
    }

    public function createCheckoutCustomerFromCustomerEntity(Customer $customer): StripeCustomer
    {
        return $this->stripe->customers->create([
            'name' => $customer->getFirstName() . ' ' . $customer->getLastName()
        ]);
    }

    public function retreiveSession(string $sessionId): Session
    {
        return $this->stripe->checkout->sessions->retrieve($sessionId);
    }

    public function retreiveCustomer(Session $session)
    {
        return $this->stripe->customers->retrieve($session->customer);
    }

    public function getTotal(array $items): float
    {
        $lineItems = $this->getLineItems($items);
        return array_reduce(
            $lineItems, 
            fn($carry, $item) => $carry + $item['quantity'] * $item['price_data']['unit_amount'], 
            0
        );
    }

}