<?php

namespace App\EventSubscriber;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function onBeforeEntityPersisted(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();
        if ($entity instanceof User) {
            $entity->setPassword($this->passwordHasher->hashPassword(
                $entity,
                $entity->getPassword()
            ));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => 'onBeforeEntityPersisted',
        ];
    }
}
