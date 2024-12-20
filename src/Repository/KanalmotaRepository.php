<?php

namespace App\Repository;

use App\Entity\Kanalmota;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Kanalmota>
 *
 * @method Kanalmota|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kanalmota|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kanalmota[]    findAll()
 * @method Kanalmota[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KanalmotaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kanalmota::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Kanalmota $entity, bool $flush = true): void
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
    public function remove(Kanalmota $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
