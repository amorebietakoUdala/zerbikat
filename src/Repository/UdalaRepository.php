<?php

namespace App\Repository;

use App\Entity\Udala;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Udala>
 *
 * @method Udala|null find($id, $lockMode = null, $lockVersion = null)
 * @method Udala|null findOneBy(array $criteria, array $orderBy = null)
 * @method Udala[]    findAll()
 * @method Udala[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UdalaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Udala::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Udala $entity, bool $flush = true): void
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
    public function remove(Udala $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Udala[] Returns an array of Udalak objects
     */

    public function findByUdala($udala)
    {
        return $this->createQueryBuilder('s')
            ->innerJoin(Udala::class,'u')
            ->andWhere('u.kodea = :udala')
            ->setParameter('udala', $udala)
            ->orderBy('s.kodea', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOrderedByIdDesc() {
        return $this->createQueryBuilder('u')->orderBy('u.kodea', 'DESC');
    }
}
