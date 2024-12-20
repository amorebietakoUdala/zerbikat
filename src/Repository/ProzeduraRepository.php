<?php

namespace App\Repository;

use App\Entity\Prozedura;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Prozedura>
 *
 * @method Prozedura|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prozedura|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prozedura[]    findAll()
 * @method Prozedura[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProzeduraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prozedura::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Prozedura $entity, bool $flush = true): void
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
    public function remove(Prozedura $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
