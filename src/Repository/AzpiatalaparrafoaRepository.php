<?php

namespace App\Repository;

use App\Entity\Azpiatalaparrafoa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Azpiatalaparrafoa>
 *
 * @method Azpiatalaparrafoa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Azpiatalaparrafoa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Azpiatalaparrafoa[]    findAll()
 * @method Azpiatalaparrafoa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AzpiatalaparrafoaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Azpiatalaparrafoa::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Azpiatalaparrafoa $entity, bool $flush = true): void
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
    public function remove(Azpiatalaparrafoa $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
