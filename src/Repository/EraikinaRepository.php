<?php

namespace App\Repository;

use App\Entity\Eraikina;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Eraikina>
 *
 * @method Eraikina|null find($id, $lockMode = null, $lockVersion = null)
 * @method Eraikina|null findOneBy(array $criteria, array $orderBy = null)
 * @method Eraikina[]    findAll()
 * @method Eraikina[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EraikinaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Eraikina::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Eraikina $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Eraikina $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Docklagun[] Returns an array of Docklagunk objects
    //  */

    // public function findByDocklagunkByUdala($udala)
    // {
    //     return $this->createQueryBuilder('s')
    //         ->innerJoin('App:Udala','u')
    //         ->andWhere('u.kodea = :udala')
    //         ->setParameter('udala', $udala)
    //         ->orderBy('s.kodea', 'DESC')
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }


    /*
    public function findOneBySomeField($value): ?Docklagunk
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
