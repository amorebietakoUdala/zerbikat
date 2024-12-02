<?php

namespace App\Repository;

use App\Entity\Zerbitzua;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Zerbitzua>
 *
 * @method Zerbitzua|null find($id, $lockMode = null, $lockVersion = null)
 * @method Zerbitzua|null findOneBy(array $criteria, array $orderBy = null)
 * @method Zerbitzua[]    findAll()
 * @method Zerbitzua[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZerbitzuaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Zerbitzua::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Zerbitzua $entity, bool $flush = true): void
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
    public function remove(Zerbitzua $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Zerbitzua[] Returns an array of Zerbitzuak objects
     */

    public function findByZerbitzua($Zerbitzua)
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('App:Zerbitzua','u')
            ->andWhere('u.kodea = :Zerbitzua')
            ->setParameter('Zerbitzua', $Zerbitzua)
            ->orderBy('s.kodea', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }


    // $query = $this->em->createQuery(
    //     /** @lang text */
    //     '
    //     SELECT s         
    //       FROM App:Zerbitzua s
    //       INNER JOIN s.Zerbitzua u
    //     WHERE u.kodea = :Zerbitzua
    //     ORDER BY s.kodea DESC
    //     '
    // );
    // $query->setParameter( 'Zerbitzua', $Zerbitzua );


    /*
    public function findOneBySomeField($value): ?Zerbitzuak
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
