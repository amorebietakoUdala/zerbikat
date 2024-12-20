<?php

namespace App\Repository;

use App\Entity\Ordenantzaparrafoa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ordenantzaparrafoa>
 *
 * @method Ordenantzaparrafoaparrafoa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ordenantzaparrafoa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ordenantzaparrafoa[]    findAll()
 * @method Ordenantzaparrafoa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdenantzaparrafoaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ordenantzaparrafoa::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Ordenantzaparrafoa $entity, bool $flush = true): void
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
    public function remove(Ordenantzaparrafoa $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
