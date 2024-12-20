<?php

namespace App\Repository;

use App\Entity\Datuenbabesa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Datuenbabesa>
 *
 * @method Datuenbabesa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Datuenbabesa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Datuenbabesa[]    findAll()
 * @method Datuenbabesa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DatuenbabesaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Datuenbabesa::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Datuenbabesa $entity, bool $flush = true): void
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
    public function remove(Datuenbabesa $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
