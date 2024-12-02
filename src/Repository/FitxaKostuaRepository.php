<?php

namespace App\Repository;

use App\Entity\FitxaKostua;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FitxaKostua>
 *
 * @method FitxaKostua|null find($id, $lockMode = null, $lockVersion = null)
 * @method FitxaKostua|null findOneBy(array $criteria, array $orderBy = null)
 * @method FitxaKostua[]    findAll()
 * @method FitxaKostua[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FitxaKostuaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FitxaKostua::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(FitxaKostua $entity, bool $flush = true): void
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
    public function remove(FitxaKostua $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /*
    public function findOneBySomeField($value): ?FitxaKostuak
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
