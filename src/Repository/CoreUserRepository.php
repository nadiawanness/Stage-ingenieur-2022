<?php

namespace App\Repository;

use App\Entity\CoreUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CoreUser>
 *
 * @method CoreUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoreUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoreUser[]    findAll()
 * @method CoreUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoreUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoreUser::class);
    }

    public function add(CoreUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CoreUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findCoreUserByType($type): array
    {
        return $this->createQueryBuilder('user')
            ->select('user')
            ->andWhere('user.type = :val')
            ->setParameter('val', $type)
            ->orderBy('user.id', 'DESC')
            ->getQuery()
            ->getResult()
   ;
   }

   public function findByOrg($admin): array 
   {
    $page = 1 ;
    $pageSize = 100 ;
        return $this->createQueryBuilder('user')
            ->select('user')
            ->leftJoin('user.organizations','org')
            ->andWhere('user.type = :type')
            ->andWhere('org.assignedTo = :idadmin')
            ->andWhere('org.status = :status')
            ->andWhere('org.enabled = :enabled')
            ->setParameter('type','core_user_additional')
            ->setParameter('idadmin',$admin)
            ->setParameter('status','valid')
            ->setParameter('enabled','1')
            ->orderBy('user.id','DESC')
            ->setFirstResult($pageSize * ($page-1))
            ->setMaxResults($pageSize)
            ->getQuery()
            ->getResult() 
            ;
   }

  /* public function findByOrganization($organization): array
   {
       return $this->createQueryBuilder('user')
           ->select('user')
           ->andWhere('user.ha')
          ->getQuery()
          ->getResult()
  ;
  } */

   public function findCoreUserByEnabled($enabled): array
   {
       return $this->createQueryBuilder('user')
           ->select('user')
           ->andWhere('user.enabled = :val')
           ->setParameter('val', $enabled)
           ->orderBy('user.id', 'DESC')
           ->getQuery()
           ->getResult()
  ;
  }

//    /**
//     * @return CoreUser[] Returns an array of CoreUser objects
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

//    public function findOneBySomeField($value): ?CoreUser
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
