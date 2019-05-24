<?php

namespace App\Repository;

use App\Entity\DeviceDriver;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DeviceDriver|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeviceDriver|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeviceDriver[]    findAll()
 * @method DeviceDriver[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviceDriverRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DeviceDriver::class);
    }

    // /**
    //  * @return DeviceDriver[] Returns an array of DeviceDriver objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DeviceDriver
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
