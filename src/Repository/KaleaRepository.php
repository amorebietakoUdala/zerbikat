<?php

namespace App\Repository;

use App\Entity\Kalea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Kalea>
 *
 * @method Kalea|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kalea|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kalea[]    findAll()
 * @method Kalea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KaleaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kalea::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Kalea $entity, bool $flush = true): void
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
    public function remove(Kalea $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
