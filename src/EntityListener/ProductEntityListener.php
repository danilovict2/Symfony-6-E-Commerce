<?php

namespace App\EntityListener;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::preRemove, entity: Product::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Product::class)]
class ProductEntityListener
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function preRemove(Product $product, LifecycleEventArgs $event)
    {
        $product->setDeletedBy($this->security->getUser());
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        if ($product->getImage()) {
            unlink('uploads/photos/' . $product->getImage());
        }
    }

    public function preUpdate(Product $product, LifecycleEventArgs $event)
    {
        if ($product->getImage()) {
            unlink('uploads/photos/' . $product->getImage());
        }
    }
}
