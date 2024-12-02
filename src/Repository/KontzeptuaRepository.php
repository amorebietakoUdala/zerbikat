<?php

namespace App\Repository;

use App\Entity\Kontzeptua;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Kontzeptua>
 *
 * @method Kontzeptua|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kontzeptua|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kontzeptua[]    findAll()
 * @method Kontzeptua[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KontzeptuaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kontzeptua::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Kontzeptua $entity, bool $flush = true): void
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
    public function remove(Kontzeptua $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Kontzeptua[] Returns an array of Kontzeptuak objects
    //  */

    // public function findByKontzeptuakByUdala($udala)
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
    public function findOneBySomeField($value): ?Kontzeptuak
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
