<?php

namespace App\Repository;

use App\Entity\VehicleOwner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VehicleOwner>
 *
 * @method VehicleOwner|null find($id, $lockMode = null, $lockVersion = null)
 * @method VehicleOwner|null findOneBy(array $criteria, array $orderBy = null)
 * @method VehicleOwner[]    findAll()
 * @method VehicleOwner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleOwnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VehicleOwner::class);
    }

//    /**
//     * @return VehicleOwner[] Returns an array of VehicleOwner objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VehicleOwner
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
