<?php

namespace App\Repository;

use App\Entity\Familia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Familia>
 *
 * @method Familia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Familia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Familia[]    findAll()
 * @method Familia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FamiliaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Familia::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Familia $entity, bool $flush = true): void
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
    public function remove(Familia $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Fitxa[] Returns an array of Fitxak objects
     */
    // $query = $em->createQuery(
    //     '
    //   SELECT f
    //     FROM App:Familia f
    //       INNER JOIN f.udala u
    //     WHERE u.kodea = :udala
    // '
    // );
    // $query->setParameter( 'udala', $udala );

    public function findByUdala($udala)
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('App:Udala','u')
            ->andWhere('u.kodea = :kodea')
            ->setParameter('kodea', $udala)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Fitxak
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
