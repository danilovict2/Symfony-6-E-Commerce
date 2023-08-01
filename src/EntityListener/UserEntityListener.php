<?php

namespace App\EntityListener;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsEntityListener(event: Events::prePersist, entity: User::class)]
class UserEntityListener
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function prePersist(User $user, LifecycleEventArgs $args)
    {
        $customer = new Customer();
        $names = explode(" ",$user->getName());
        $customer->setFirstName($names[0]);
        $customer->setLastName($names[1] ?? '');
        
        $this->entityManager->persist($customer);
        $this->entityManager->flush();

        $user->setCustomer($customer);
    }
}

