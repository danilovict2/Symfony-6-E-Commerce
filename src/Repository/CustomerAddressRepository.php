<?php

namespace App\Repository;

use App\Entity\Country;
use App\Entity\CustomerAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomerAddress>
 *
 * @method CustomerAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerAddress[]    findAll()
 * @method CustomerAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerAddress::class);
    }

    public function createCustomerAddress(array $data): CustomerAddress
    {
        $country = $this->getEntityManager()->getRepository(Country::class)->findOneBy(['code' => $data['country']]);
        $address = new CustomerAddress();
        $address
            ->setAddress1($data['address1'])
            ->setAddress2($data['address2'])
            ->setCity($data['city'])
            ->setState($data['state'])
            ->setCountry($country)
            ->setZipcode($data['zipcode'])
        ;

        $this->save($address);
        return $address;
    }

    public function save(CustomerAddress $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CustomerAddress $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
