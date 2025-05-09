<?php

namespace App\Repository;

use App\Entity\Norkeskatu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Norkeskatu>
 *
 * @method Norkeskatu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Norkeskatu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Norkeskatu[]    findAll()
 * @method Norkeskatu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NorkeskatuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Norkeskatu::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Norkeskatu $entity, bool $flush = true): void
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
    public function remove(Norkeskatu $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
