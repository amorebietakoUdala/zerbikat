<?php

namespace App\Repository;

use App\Entity\Fitxafamilia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fitxafamilia>
 *
 * @method Fitxafamilia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fitxafamilia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fitxafamilia[]    findAll()
 * @method Fitxafamilia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FitxafamiliaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fitxafamilia::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Fitxafamilia $entity, bool $flush = true): void
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
    public function remove(Fitxafamilia $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /*
    public function findOneBySomeField($value): ?Fitxafamiliak
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
