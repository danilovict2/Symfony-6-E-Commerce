<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Payment;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function createOrderAndLinkItToPayment(array $data, Payment &$payment): Order
    {
        $order = new Order();
        $order->setTotalPrice($data['total_price'])
            ->setStatus($data['status'])
            ->setPayment($payment)
        ;
        $this->createOrderItemsForOrder($order, $data['products']);
        $payment->setRelatedOrder($order);

        $this->save($order);
        $this->getEntityManager()->persist($payment);
        return $order;
    }

    private function createOrderItemsForOrder(Order &$order, array $products): void
    {
        $orderItemRepository = $this->getEntityManager()->getRepository(OrderItem::class);
        $productRepository = $this->getEntityManager()->getRepository(Product::class);

        foreach ($products as $product) {
            $orderItem = $orderItemRepository->createOrderItem([
                'order' => $order,
                'product' => $productRepository->find($product['id']),
                'quantity' => $product['quantity'],
                'unit_price' => $product['price']
            ]);
            $order->addOrderItem($orderItem);
        }
    }

    public function getRecentOrders(): array
    {
        return $this->findBy([], limit: 10);
    }

    public function save(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
