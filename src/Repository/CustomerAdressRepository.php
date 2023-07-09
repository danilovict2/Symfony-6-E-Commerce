<?php

namespace App\Repository;

use App\Entity\CustomerAdress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomerAdress>
 *
 * @method CustomerAdress|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerAdress|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerAdress[]    findAll()
 * @method CustomerAdress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerAdressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerAdress::class);
    }

    public function save(CustomerAdress $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CustomerAdress $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
