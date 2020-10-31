<?php

namespace App\Repository;

use App\Entity\StationRack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StationRack|null find($id, $lockMode = null, $lockVersion = null)
 * @method StationRack|null findOneBy(array $criteria, array $orderBy = null)
 * @method StationRack[]    findAll()
 * @method StationRack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StationRackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StationRack::class);
    }

    // /**
    //  * @return StationRack[] Returns an array of StationRack objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StationRack
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function save(StationRack $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function getFreeRaksForStation(string $id): int
    {
        $query = $this->createQueryBuilder('stationRack')
            ->select('COUNT(stationRack.number) as freeRacks')
            ->where('stationRack.stationName = :stationName')->setParameter('stationName', $id)
            ->andWhere('stationRack.free = 1')
            ->getQuery();

        return (int) $query->getSingleScalarResult();
    }
}
