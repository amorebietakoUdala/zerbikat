<?php

namespace App\Repository;

use App\Entity\Eremuak;
use App\Entity\Udala;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Eremuak>
 *
 * @method Eremuak|null find($id, $lockMode = null, $lockVersion = null)
 * @method Eremuak|null findOneBy(array $criteria, array $orderBy = null)
 * @method Eremuak[]    findAll()
 * @method Eremuak[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EremuakRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Eremuak::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Eremuak $entity, bool $flush = true): void
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
    public function remove(EremuaK $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Eremuak[] Returns an array of Eremuak objects
     */

    public function findOneByUdala($udala)
    {
        $qb = $this->createQueryBuilder('e')
            ->select('e.oharraktext,e.helburuatext,e.ebazpensinpli,e.arduraaitorpena,e.aurreikusi,e.arrunta,e.isiltasunadmin,e.norkeskatutext,e.norkeskatutable,e.dokumentazioatext,e.dokumentazioatable,e.kostuatext,e.kostuatable,e.araudiatext,e.araudiatable,e.prozeduratext,e.prozeduratable,e.doklaguntext,e.doklaguntable,e.datuenbabesatext,e.datuenbabesatable,e.norkebatzitext,e.norkebatzitable,e.besteak1text,e.besteak1table,e.besteak2text,e.besteak2table,e.besteak3text,e.besteak3table,e.kanalatext,e.kanalatable,e.azpisailatable')
            ->andWhere('e.udala = :udala')
            ->setParameter('udala', $udala)
//            ->orderBy('s.kodea', 'DESC')
        ;

        dump($qb->getQuery()->getSQL());

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * @return Eremuak[] Returns an array of Eremuak objects
     */
    public function findOneByUdalKodea($udalKodea)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('e.oharraktext,e.helburuatext,e.ebazpensinpli,e.arduraaitorpena,e.aurreikusi,e.arrunta,e.isiltasunadmin,e.norkeskatutext,e.norkeskatutable,e.dokumentazioatext,e.dokumentazioatable,e.kostuatext,e.kostuatable,e.araudiatext,e.araudiatable,e.prozeduratext,e.prozeduratable,e.doklaguntext,e.doklaguntable,e.datuenbabesatext,e.datuenbabesatable,e.norkebatzitext,e.norkebatzitable,e.besteak1text,e.besteak1table,e.besteak2text,e.besteak2table,e.besteak3text,e.besteak3table,e.kanalatext,e.kanalatable,e.azpisailatable');
        $qb = $this->andWhereUdalKodeaQB($qb, $udalKodea);
        return $qb->getQuery()->getSingleResult();
    }

    private function andWhereUdalKodeaQB ($qb, $udalKodea): QueryBuilder {
        $qb->leftJoin(Udala::class,'u', Join::WITH, 'e.udala=u.id')
        ->andWhere('u.kodea = :udalaKodea')
        ->setParameter('udalaKodea', $udalKodea);
        return $qb;
    }

    public function findLabelakByUdala($udala) {
        return $this->createQueryBuilder('e')
            ->select('e.oharraklabeleu,e.oharraklabeles,e.helburualabeleu,e.helburualabeles,e.ebazpensinplilabeleu,e.ebazpensinplilabeles,e.arduraaitorpenalabeleu,e.arduraaitorpenalabeles,e.aurreikusilabeleu,e.aurreikusilabeles,e.arruntalabeleu,e.arruntalabeles,e.isiltasunadminlabeleu,e.isiltasunadminlabeles,e.norkeskatulabeleu,e.norkeskatulabeles,e.dokumentazioalabeleu,e.dokumentazioalabeles,e.kostualabeleu,e.kostualabeles,e.araudialabeleu,e.araudialabeles,e.prozeduralabeleu,e.prozeduralabeles,e.doklagunlabeleu,e.doklagunlabeles,e.datuenbabesalabeleu,e.datuenbabesalabeles,e.norkebatzilabeleu,e.norkebatzilabeles,e.besteak1labeleu,e.besteak1labeles,e.besteak2labeleu,e.besteak2labeles,e.besteak3labeleu,e.besteak3labeles,e.kanalalabeleu,e.kanalalabeles,e.epealabeleu,e.epealabeles,e.doanlabeleu,e.doanlabeles,e.azpisailalabeleu,e.azpisailalabeles')
            ->andWhere('e.udala = :udala')
            ->setParameter('udala', $udala)
            ->getQuery()
            ->getSingleResult()
        ;
    }

    public function findLabelakByUdalKodea($udalKodea) {
        $qb = $this->createQueryBuilder('e');
        $qb->select('e.oharraklabeleu,e.oharraklabeles,e.helburualabeleu,e.helburualabeles,e.ebazpensinplilabeleu,e.ebazpensinplilabeles,e.arduraaitorpenalabeleu,e.arduraaitorpenalabeles,e.aurreikusilabeleu,e.aurreikusilabeles,e.arruntalabeleu,e.arruntalabeles,e.isiltasunadminlabeleu,e.isiltasunadminlabeles,e.norkeskatulabeleu,e.norkeskatulabeles,e.dokumentazioalabeleu,e.dokumentazioalabeles,e.kostualabeleu,e.kostualabeles,e.araudialabeleu,e.araudialabeles,e.prozeduralabeleu,e.prozeduralabeles,e.doklagunlabeleu,e.doklagunlabeles,e.datuenbabesalabeleu,e.datuenbabesalabeles,e.norkebatzilabeleu,e.norkebatzilabeles,e.besteak1labeleu,e.besteak1labeles,e.besteak2labeleu,e.besteak2labeles,e.besteak3labeleu,e.besteak3labeles,e.kanalalabeleu,e.kanalalabeles,e.epealabeleu,e.epealabeles,e.doanlabeleu,e.doanlabeles,e.azpisailalabeleu,e.azpisailalabeles');
        $this->andWhereUdalKodeaQB($qb, $udalKodea);
        return $qb->getQuery()->getSingleResult();
    }
}
