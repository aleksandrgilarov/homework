<?php

namespace App\Repository;

use App\Entity\IpLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IpLocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method IpLocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method IpLocation[]    findAll()
 * @method IpLocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IpLocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IpLocation::class);
    }

    public function findOneByIp($ip): ?IpLocation
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.ip = :val')
            ->setParameter('val', $ip)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
