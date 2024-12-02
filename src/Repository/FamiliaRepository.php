<?php

namespace App\Repository;

use App\Entity\Familia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
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
    
    // $sqlFamiliak = 
    // /** @lang text */
    // '
    //     SELECT f, COALESCE (f.ordena,0) as HIDDEN ezkutuan            
    //     FROM App:Familia f
    //     LEFT JOIN App:Udala u WITH f.udala=u.id
    //     WHERE u.kodea = :udala AND f.parent is NULL';
    // if (null !== $familia) {
    //     $sqlFamiliak = $sqlFamiliak. ' AND f.id = :familia';
    // }
    // $sqlFamiliak = $sqlFamiliak.' ORDER BY f.familia'.$request->getLocale().' ASC';
    // // dump($sqlFamiliak);die;
    // $query = $em->createQuery($sqlFamiliak);
    // $query->setParameter( 'udala', $udala );
    // if (null !== $familia) {
    //     $query->setParameter( 'familia', $familia );
    // }

    public function findByUdalaAndParentAndFamiliaId($udala, $locale, $parent = null, $familiaId = null) {
        $qb = $this->createQueryBuilder('f')
            ->select('f, COALESCE (f.ordena, 0) as HIDDEN ezkutuan');
        $this->andWhereUdalaQB($qb, $udala);
        $this->andWhereParentQB($qb, null);
        if ( null !== $familiaId ) {
            $this->andWhereFamiliaId($qb, $familiaId);
        }
        $qb->orderBy("f.familia$locale", "ASC");
        return $qb->getQuery()->getResult();
    }

    private function lefJoinUdalaQB($qb): QueryBuilder {
        $qb->leftJoin('App:Udala','u', Join::WITH, 'f.udala = u.id');
        return $qb;
    }

    private function andWhereUdalaQB($qb, $udala): QueryBuilder {
        $qb = $this->lefJoinUdalaQB($qb);
        $qb->andWhere('u.kodea = :udala');
        $qb->setParameter('udala', $udala);
        return $qb;
    }

    private function andWhereFamiliaId($qb, $familiaId) {
        $qb->andWhere('f.id = :familiaId');
        $qb->setParameter('familiaId', $familiaId);
        return $qb;
    }

    private function andWhereParentQB($qb, $parent = null) {
        if ( null === $parent ) {
            $qb->andWhere('f.parent is NULL');
        } else {
            $qb->andWhere('f.parent = :parent');
            $qb->setParameter('parent', $parent);
        }
        return $qb;
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
