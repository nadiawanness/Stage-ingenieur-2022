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

    public function findCoreUserByType($type)
    {
        return $this->createQueryBuilder('user')
            ->select('user')
            ->andWhere('user.type = :val')
            ->setParameter('val', $type)
            ->orderBy('user.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findCoreUserByOrg($admin, $isSearch = false, $content = null)
    {
        $qb = $this->createQueryBuilder('user')
            ->select('user')
            ->leftJoin('user.organizations', 'org')
            ->andWhere('user.type = :type')
            ->andWhere('org.assignedTo = :idadmin')
            ->andWhere('org.status = :status')
            ->andWhere('org.enabled = :enabled')
            ->setParameter('type', 'core_user_additional')
            ->setParameter('idadmin', $admin)
            ->setParameter('status', 'valid')
            ->setParameter('enabled', '1');
        if ($isSearch) {
            $this->filtreUser($qb, $content);
        } else {
            return $qb
            ->orderBy('user.id', 'DESC')
            ->getQuery()
            ->getResult();
        }
    }

    /**
     * findUserByEmail
     * find user by email.
     *
     * @param mixed $email
     *
     * @return void
     */
    public function findUserByEmail($email)
    {
        return $this->createQueryBuilder('user')
            ->select('user')
            ->andWhere('user.email = :email')
            ->setParameter('email', $email)
            ->orderBy('user.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * filtreUser
     * filter user.
     *
     * @param mixed $query
     * @param mixed $content
     *
     * @return void
     */
    private function filtreUser($query, $content)
    {
        return $query
            ->andWhere('user.email LIKE :email')
            ->setParameter('email', $content['email']);
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
