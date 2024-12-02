<?php

namespace App\Repository;

use App\Entity\Aurreikusi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Aurreikusi>
 *
 * @method Aurreikusi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Aurreikusi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Aurreikusi[]    findAll()
 * @method Aurreikusi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AurreikusiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Aurreikusi::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Aurreikusi $entity, bool $flush = true): void
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
    public function remove(Aurreikusi $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Aurreikusi[] Returns an array of Aurreikusik objects
    //  */

    // public function findByAurreikusikByUdala($udala)
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
    public function findOneBySomeField($value): ?Aurreikusik
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
