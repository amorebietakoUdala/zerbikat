<?php

namespace App\Repository;

use App\Entity\Dokumentumota;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dokumentumota>
 *
 * @method Dokumentumota|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dokumentumota|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dokumentumota[]    findAll()
 * @method Dokumentumota[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DokumentumotaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dokumentumota::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Dokumentumota $entity, bool $flush = true): void
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
    public function remove(Dokumentumota $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
