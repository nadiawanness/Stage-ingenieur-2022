<?php

namespace App\Repository;

use App\Entity\CoreUserAgencies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CoreUserAgencies>
 *
 * @method CoreUserAgencies|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoreUserAgencies|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoreUserAgencies[]    findAll()
 * @method CoreUserAgencies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoreUserAgenciesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoreUserAgencies::class);
    }

    public function add(CoreUserAgencies $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CoreUserAgencies $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CoreUserAgencies[] Returns an array of CoreUserAgencies objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CoreUserAgencies
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
