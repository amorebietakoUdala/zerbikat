<?php

namespace App\Repository;

use App\Entity\Azpisaila;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Azpisaila>
 *
 * @method Azpisaila|null find($id, $lockMode = null, $lockVersion = null)
 * @method Azpisaila|null findOneBy(array $criteria, array $orderBy = null)
 * @method Azpisaila[]    findAll()
 * @method Azpisaila[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AzpisailaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Azpisaila::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Azpisaila $entity, bool $flush = true): void
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
    public function remove(Azpisaila $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
