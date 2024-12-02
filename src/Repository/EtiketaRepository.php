<?php

namespace App\Repository;

use App\Entity\Etiketa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Etiketa>
 *
 * @method Etiketa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etiketa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etiketa[]    findAll()
 * @method Etiketa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtiketaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etiketa::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Etiketa $entity, bool $flush = true): void
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
    public function remove(Etiketa $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /*
    public function findOneBySomeField($value): ?Etiketa
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
