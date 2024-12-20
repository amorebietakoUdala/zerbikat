<?php

namespace App\Repository;

use App\Entity\Norkebatzi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Norkebatzi>
 *
 * @method Norkebatzi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Norkebatzi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Norkebatzi[]    findAll()
 * @method Norkebatzi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NorkebatziRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Norkebatzi::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Norkebatzi $entity, bool $flush = true): void
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
    public function remove(Norkebatzi $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
