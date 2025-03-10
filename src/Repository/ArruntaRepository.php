<?php

namespace App\Repository;

use App\Entity\Arrunta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Arrunta>
 *
 * @method Arrunta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Arrunta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Arrunta[]    findAll()
 * @method Arrunta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArruntaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Arrunta::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Arrunta $entity, bool $flush = true): void
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
    public function remove(Arrunta $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
