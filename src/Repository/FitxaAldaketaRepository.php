<?php

namespace App\Repository;

use App\Entity\FitxaAldaketa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FitxaAldaketa>
 *
 * @method FitxaAldaketa|null find($id, $lockMode = null, $lockVersion = null)
 * @method FitxaAldaketa|null findOneBy(array $criteria, array $orderBy = null)
 * @method FitxaAldaketa[]    findAll()
 * @method FitxaAldaketa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FitxaAldaketaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FitxaAldaketa::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(FitxaAldaketa $entity, bool $flush = true): void
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
    public function remove(FitxaAldaketa $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return FitxaAldaketa[] Returns an array of FitxaAldaketak objects
    //  */

    // public function findByFitxaAldaketakByUdala($udala)
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
    public function findOneBySomeField($value): ?FitxaAldaketak
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
