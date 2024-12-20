<?php

namespace App\Repository;

use App\Entity\Dokumentazioa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dokumentazioa>
 *
 * @method Dokumentazioa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dokumentazioa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dokumentazioa[]    findAll()
 * @method Dokumentazioa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DokumentazioaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dokumentazioa::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Dokumentazioa $entity, bool $flush = true): void
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
    public function remove(Dokumentazioa $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
