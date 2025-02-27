<?php

namespace App\Repository;

use App\Entity\Azpisaila;
use App\Entity\Familia;
use App\Entity\Fitxa;
use App\Entity\Fitxafamilia;
use App\Entity\Udala;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fitxa>
 *
 * @method Fitxa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fitxa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fitxa[]    findAll()
 * @method Fitxa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FitxaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fitxa::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Fitxa $entity, bool $flush = true): void
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
    public function remove(Fitxa $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    // $query = $this->em->createQuery(
    //     'SELECT f 
    //           FROM App:Fitxa f
    //           LEFT JOIN f.azpisaila a
    //           ORDER BY a.saila ASC, f.azpisaila ASC        '
    // );

    /**
     * @return Fitxa[] Returns an array of Fitxak objects
     */
    public function findAzpisailakOrderedBySailakAzpisailak() {
        return $this->createQueryBuilder('f')
            ->leftJoin(Azpisaila::class,'a', Join::WITH, 'f.azpisaila = a.id')
            ->addOrderBy('a.saila', 'ASC')
            ->addOrderBy('f.azpisaila', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Fitxa[] Returns an array of Fitxak objects
     */
    public function findByAzpisaila($azpisailaid)
    {
        return $this->createQueryBuilder('f')
            ->innerJoin(Azpisaila::class,'a')
            ->andWhere('a.id = :azpisailaid')
            ->setParameter('azpisailaid', $azpisailaid)
            ->andWhere('f.publikoa = 1')
            ->getQuery()
            ->getResult()
        ;
    }

    // $query = $em->createQuery(
    //     /** @lang text */
    //     '
    //     SELECT f.id,f.espedientekodea,f.deskribapenaeu,f.deskribapenaes
    //     FROM App:Fitxa f
    //       LEFT JOIN f.familiak ff
    //     WHERE ff.id = :id AND f.publikoa=1
    //     '
    // );

    public function findByFamilia($familia)
    {
        $result = $this->createQueryBuilder('fi')
            ->select('fi.id,fi.espedientekodea,fi.deskribapenaeu,fi.deskribapenaes')
            ->leftJoin(Fitxafamilia::class,'ff', Join::WITH, 'ff.fitxa = fi.id')
            ->leftJoin(Familia::class,'fa', Join::WITH, 'ff.familia = fa.id')
            ->andWhere('fa.id = :familia')
            ->setParameter('familia', $familia)
            ->andWhere('fi.publikoa = 1')
            ->getQuery()
            ->getResult()
        ;
        return $result;
    }


    // $sqlFitxak = 
    // /** @lang text */
    // '
    //     SELECT f 
    //       FROM App:Fitxa f 
    //       LEFT JOIN App:Udala u  WITH f.udala=u.id ';
    // if (null !== $azpisaila) {
    //     $sqlFitxak = $sqlFitxak.' LEFT JOIN App:azpisaila az  WITH f.azpisaila=az.id ';
    // }
    // // if (null !== $familia) {
    // //     $sqlFitxak = $sqlFitxak.' LEFT JOIN App:familia fam  WITH f.azpisaila=fam.id '
    // // }
    // $sqlFitxak = $sqlFitxak.' WHERE u.kodea = :udala ';
    // if (null !== $azpisaila) {
    //     $sqlFitxak = $sqlFitxak.' AND f.azpisaila = :azpisaila ';
    // }
    // $sqlFitxak = $sqlFitxak.' AND f.publikoa = 1 ';
    // $sqlFitxak = $sqlFitxak.' ORDER BY f.kontsultak DESC';

    // $query = $em->createQuery($sqlFitxak);
    // if (null !== $azpisaila) {
    //     $query->setParameter( 'azpisaila', $azpisaila );    
    // }
    // $query->setParameter( 'udala', $udala );
    // $fitxak = $query->getResult();

    public function findPublicByUdalaAndAzpisaila($udala,  $azpisaila) {
        $qb = $this->createQueryBuilder('f');
        if (null !== $azpisaila) {
            $this->andWhereAzpisailaQB($qb, $azpisaila);
        }
        $this->andWhereUdalaQB($qb, $udala);
        $this->andWherePublicQB($qb, true);
        $qb->orderBy('f.kontsultak', 'DESC');
        return $qb->getQuery()->getResult();
    }

    private function andWherePublicQB($qb, $public) {
        $qb->andWhere('f.publikoa = :public');
        $qb->setParameter('public', $public);
        return $qb;
    }

    private function andWhereUdalaQB( QueryBuilder $qb, $udala): QueryBuilder 
    {
        $qb->leftJoin(Udala::class,'u', Join::WITH, 'f.udala = u.id');
        $qb->andWhere('u.kodea = :udala');
        $qb->setParameter('udala', $udala);
        return $qb;
    }

    private function andWhereAzpisailaQB( QueryBuilder $qb, $azpisaila): QueryBuilder 
    {
        $qb->leftJoin(Azpisaila::class,'az', Join::WITH, 'f.azpisaila = az.id');
        $qb->andWhere('f.azpisaila = :azpisaila');
        $qb->setParameter('azpisaila', $azpisaila);
        return $qb;
    }
}
