<?php

namespace App\Repository;

use App\Entity\Saila;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Saila>
 *
 * @method Saila|null find($id, $lockMode = null, $lockVersion = null)
 * @method Saila|null findOneBy(array $criteria, array $orderBy = null)
 * @method Saila[]    findAll()
 * @method Saila[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SailaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Saila::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Saila $entity, bool $flush = true): void
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
    public function remove(Saila $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Saila[] Returns an array of Sailak objects
     */

    public function findByUdala($udala)
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('App:Udala','u')
            ->andWhere('u.kodea = :udala')
            ->setParameter('udala', $udala)
            ->orderBy('s.kodea', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }


    // $query = $this->em->createQuery(
    //     /** @lang text */
    //     '
    //     SELECT s         
    //       FROM App:Saila s
    //       INNER JOIN s.udala u
    //     WHERE u.kodea = :udala
    //     ORDER BY s.kodea DESC
    //     '
    // );
    // $query->setParameter( 'udala', $udala );


    /*
    public function findOneBySomeField($value): ?Sailak
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
