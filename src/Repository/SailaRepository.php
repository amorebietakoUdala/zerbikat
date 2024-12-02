<?php

namespace App\Repository;

use App\Entity\Saila;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
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

    // $sqlSailak =
    // /** @lang text */
    // '
    //     SELECT s            
    //       FROM App:Saila s
    //       LEFT JOIN App:Udala u WITH s.udala=u.id ';
    // if (null !== $azpisaila) {
    //     $sqlSailak = $sqlSailak. 'LEFT JOIN App:Azpisaila az WITH az.saila=s.id ';
    // }
    // $sqlSailak = $sqlSailak.' WHERE u.kodea = :udala ';
    // if (null !== $azpisaila) {
    //     $sqlSailak = $sqlSailak.' AND az.id = :azpisaila ';
    // }
    // $sqlSailak = $sqlSailak.'ORDER BY s.saila'.$request->getLocale().' ASC';

    // $query = $em->createQuery($sqlSailak);

    // if (null !== $azpisaila) {
    //     $query->setParameter( 'azpisaila', $azpisaila );
    // }            
    // $query->setParameter( 'udala', $udala );

    public function findByUdalaAndAzpisaila ($udala, $azpisaila) {
        $qb = $this->createQueryBuilder('s');
        $this->andWhereUdalaQB($qb, $udala);
        if ( null !== $azpisaila) {
            $qb = $this->andWhereAzpisailaQB($qb, $azpisaila);
        }
        return $qb->getQuery()->getResult();
    }

    private function lefJoinUdalaQB($qb): QueryBuilder {
        $qb->leftJoin('App:Udala','u', Join::WITH, 's.udala = u.id');
        return $qb;
    }

    private function andWhereUdalaQB($qb, $udala): QueryBuilder {
        $qb = $this->lefJoinUdalaQB($qb);
        $qb->andWhere('u.kodea = :udala');
        $qb->setParameter('udala', $udala);
        return $qb;
    }

    private function leftJoinAzpisailaQB($qb): QueryBuilder {
        $qb->leftJoin('App:Azpisaila','az', Join::WITH, 'az.saila=s.id');
        return $qb;
    }

    private function andWhereAzpisailaQB( QueryBuilder $qb, $azpisaila): QueryBuilder 
    {
        $qb = $this->leftJoinAzpisailaQB($qb);
        $qb->andWhere('s.azpisaila = :azpisaila');
        $qb->setParameter('azpisaila', $azpisaila);
        return $qb;
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
