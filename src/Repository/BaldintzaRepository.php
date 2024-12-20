<?php

namespace App\Repository;

use App\Entity\Baldintza;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Baldintza>
 *
 * @method Baldintza|null find($id, $lockMode = null, $lockVersion = null)
 * @method Baldintza|null findOneBy(array $criteria, array $orderBy = null)
 * @method Baldintza[]    findAll()
 * @method Baldintza[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaldintzaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Baldintza::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Baldintza $entity, bool $flush = true): void
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
    public function remove(Baldintza $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
