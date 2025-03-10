<?php

namespace App\Repository;

use App\Entity\FitxaAraudia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FitxaAraudia>
 *
 * @method FitxaAraudia|null find($id, $lockMode = null, $lockVersion = null)
 * @method FitxaAraudia|null findOneBy(array $criteria, array $orderBy = null)
 * @method FitxaAraudia[]    findAll()
 * @method FitxaAraudia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FitxaAraudiaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FitxaAraudia::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(FitxaAraudia $entity, bool $flush = true): void
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
    public function remove(FitxaAraudia $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
