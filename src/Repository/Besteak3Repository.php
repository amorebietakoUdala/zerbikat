<?php

namespace App\Repository;

use App\Entity\Besteak3;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Besteak3>
 *
 * @method Besteak3|null find($id, $lockMode = null, $lockVersion = null)
 * @method Besteak3|null findOneBy(array $criteria, array $orderBy = null)
 * @method Besteak3[]    findAll()
 * @method Besteak3[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Besteak3Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Besteak3::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Besteak3 $entity, bool $flush = true): void
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
    public function remove(Besteak3 $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
