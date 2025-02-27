<?php

namespace App\Repository;

use App\Entity\Azpisaila;
use App\Entity\Saila;
use App\Entity\Udala;
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
            ->innerJoin(Udala::class,'u')
            ->andWhere('u.kodea = :udala')
            ->setParameter('udala', $udala)
            ->orderBy('s.kodea', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByUdalaAndAzpisaila ($udala, $azpisaila) {
        $qb = $this->createQueryBuilder('s');
        $this->andWhereUdalaQB($qb, $udala);
        if ( null !== $azpisaila) {
            $qb = $this->andWhereAzpisailaQB($qb, $azpisaila);
        }
        return $qb->getQuery()->getResult();
    }

    private function lefJoinUdalaQB($qb): QueryBuilder {
        $qb->leftJoin(Udala::class,'u', Join::WITH, 's.udala = u.id');
        return $qb;
    }

    private function andWhereUdalaQB($qb, $udala): QueryBuilder {
        $qb = $this->lefJoinUdalaQB($qb);
        $qb->andWhere('u.kodea = :udala');
        $qb->setParameter('udala', $udala);
        return $qb;
    }

    private function leftJoinAzpisailaQB($qb): QueryBuilder {
        $qb->leftJoin(Azpisaila::class,'az', Join::WITH, 'az.saila=s.id');
        return $qb;
    }

    private function andWhereAzpisailaQB( QueryBuilder $qb, $azpisaila): QueryBuilder 
    {
        $qb = $this->leftJoinAzpisailaQB($qb);
        $qb->andWhere('s.azpisaila = :azpisaila');
        $qb->setParameter('azpisaila', $azpisaila);
        return $qb;
    }
}
