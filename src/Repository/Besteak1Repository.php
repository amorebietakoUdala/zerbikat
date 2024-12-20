<?php

namespace App\Repository;

use App\Entity\Besteak1;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Besteak1>
 *
 * @method Besteak1|null find($id, $lockMode = null, $lockVersion = null)
 * @method Besteak1|null findOneBy(array $criteria, array $orderBy = null)
 * @method Besteak1[]    findAll()
 * @method Besteak1[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Besteak1Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Besteak1::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Besteak1 $entity, bool $flush = true): void
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
    public function remove(Besteak1 $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
