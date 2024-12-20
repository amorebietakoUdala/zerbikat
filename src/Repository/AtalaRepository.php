<?php

namespace App\Repository;

use App\Entity\Atala;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Atala>
 *
 * @method Atala|null find($id, $lockMode = null, $lockVersion = null)
 * @method Atala|null findOneBy(array $criteria, array $orderBy = null)
 * @method Atala[]    findAll()
 * @method Atala[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AtalaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Atala::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Atala $entity, bool $flush = true): void
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
    public function remove(Atala $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
