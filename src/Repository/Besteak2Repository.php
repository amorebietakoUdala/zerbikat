<?php

namespace App\Repository;

use App\Entity\Besteak2;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Besteak2>
 *
 * @method Besteak2|null find($id, $lockMode = null, $lockVersion = null)
 * @method Besteak2|null findOneBy(array $criteria, array $orderBy = null)
 * @method Besteak2[]    findAll()
 * @method Besteak2[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Besteak2Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Besteak2::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Besteak2 $entity, bool $flush = true): void
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
    public function remove(Besteak2 $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
