<?php

namespace App\Repository;

use App\Entity\Barrutia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Barrutia>
 *
 * @method Barrutia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Barrutia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Barrutia[]    findAll()
 * @method Barrutia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BarrutiaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Barrutia::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Barrutia $entity, bool $flush = true): void
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
    public function remove(Barrutia $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
