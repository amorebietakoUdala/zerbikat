<?php

namespace App\Repository;

use App\Entity\IsiltasunAdministratiboa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IsiltasunAdministratiboa>
 *
 * @method IsiltasunAdministratiboa|null find($id, $lockMode = null, $lockVersion = null)
 * @method IsiltasunAdministratiboa|null findOneBy(array $criteria, array $orderBy = null)
 * @method IsiltasunAdministratiboa[]    findAll()
 * @method IsiltasunAdministratiboa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IsiltasunAdministratiboaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IsiltasunAdministratiboa::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(IsiltasunAdministratiboa $entity, bool $flush = true): void
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
    public function remove(IsiltasunAdministratiboa $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /*
    public function findOneBySomeField($value): ?IsiltasunAdministratiboak
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
