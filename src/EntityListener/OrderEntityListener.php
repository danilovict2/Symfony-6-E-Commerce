<?php

namespace App\EntityListener;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, entity: Order::class)]
class OrderEntityListener
{
    public function __construct(
        private Security $security
    ) {
    }

    public function prePersist(Order $orderItem, LifecycleEventArgs $args)
    {
        $orderItem->setCreatedBy($this->security->getUser());
    }
}
