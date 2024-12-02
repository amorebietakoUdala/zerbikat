<?php

namespace App\Repository;

use App\Entity\Fitxa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    /**
     * @return Fitxa[] Returns an array of Fitxak objects
     */

    public function findByAzpisaila($azpisailaid)
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('App:Azpisaila','a')
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
            ->leftJoin('App:Fitxafamilia','ff', Join::WITH, 'ff.fitxa = fi.id')
            ->leftJoin('App:Familia','fa', Join::WITH, 'ff.familia = fa.id')
            ->andWhere('fa.id = :familia')
            ->setParameter('familia', $familia)
            ->andWhere('fi.publikoa = 1')
            ->getQuery()
            ->getResult()
        ;
        return $result;
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
