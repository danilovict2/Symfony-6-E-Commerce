<?php

namespace App\Repository;

use App\Entity\CartItem;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CartItem>
 *
 * @method CartItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartItem[]    findAll()
 * @method CartItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartItem::class);
    }

    public function getUserCartItemsCount(User $user): int
    {
        return $this->getUserCartItemsQuery($user)->select('SUM(quantity)')->getQuery()->execute();
    }

    public function getUserCartItems(User $user): array
    {
        return $this->getUserCartItemsQuery($user)->select('product, quantity')->getQuery()->execute();
    }

    public function getUserCartItemsGroupedByProduct(User $user): array
    {
        return $this->getUserCartItemsQuery($user)->select('product, quantity')->groupBy('product')->getQuery()->execute();
    }

    private function getUserCartItemsQuery(User $user): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
        ;
    }

    public function save(CartItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CartItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
