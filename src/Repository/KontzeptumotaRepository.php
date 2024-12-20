<?php

namespace App\Repository;

use App\Entity\Kontzeptumota;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Kontzeptumota>
 *
 * @method Kontzeptumota|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kontzeptumota|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kontzeptumota[]    findAll()
 * @method Kontzeptumota[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KontzeptumotaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kontzeptumota::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Kontzeptumota $entity, bool $flush = true): void
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
    public function remove(Kontzeptumota $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
