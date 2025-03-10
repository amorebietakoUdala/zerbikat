<?php

namespace App\Repository;

use App\Entity\Araumota;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Araumota>
 *
 * @method Araumota|null find($id, $lockMode = null, $lockVersion = null)
 * @method Araumota|null findOneBy(array $criteria, array $orderBy = null)
 * @method Araumota[]    findAll()
 * @method Araumota[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AraumotaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Araumota::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Araumota $entity, bool $flush = true): void
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
    public function remove(Araumota $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
