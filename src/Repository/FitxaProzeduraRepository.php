<?php

namespace App\Repository;

use App\Entity\FitxaProzedura;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FitxaProzedura>
 *
 * @method FitxaProzedura|null find($id, $lockMode = null, $lockVersion = null)
 * @method FitxaProzedura|null findOneBy(array $criteria, array $orderBy = null)
 * @method FitxaProzedura[]    findAll()
 * @method FitxaProzedura[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FitxaProzeduraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FitxaProzedura::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(FitxaProzedura $entity, bool $flush = true): void
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
    public function remove(FitxaProzedura $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /*
    public function findOneBySomeField($value): ?FitxaProzedurak
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
