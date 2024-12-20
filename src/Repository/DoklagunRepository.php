<?php

namespace App\Repository;

use App\Entity\Doklagun;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Doklagun>
 *
 * @method Doklagun|null find($id, $lockMode = null, $lockVersion = null)
 * @method Doklagun|null findOneBy(array $criteria, array $orderBy = null)
 * @method Doklagun[]    findAll()
 * @method Doklagun[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoklagunRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Doklagun::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Doklagun $entity, bool $flush = true): void
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
    public function remove(Doklagun $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
