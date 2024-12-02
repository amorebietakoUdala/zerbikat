<?php

namespace App\Repository;

use App\Entity\Espedientekudeaketa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Espedientekudeaketa>
 *
 * @method Espedientekudeaketa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Espedientekudeaketa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Espedientekudeaketa[]    findAll()
 * @method Espedientekudeaketa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EspedientekudeaketaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Espedientekudeaketa::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Espedientekudeaketa $entity, bool $flush = true): void
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
    public function remove(Espedientekudeaketa $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    // $query = $em->createQuery(
    //     /** @lang text */
    //     '
    //   SELECT f.oharraktext,f.helburuatext,f.ebazpensinpli,f.arduraaitorpena,f.aurreikusi,f.arrunta,f.isiltasunadmin,f.norkeskatutext,f.norkeskatutable,f.dokumentazioatext,f.dokumentazioatable,f.kostuatext,f.kostuatable,f.araudiatext,f.araudiatable,f.prozeduratext,f.prozeduratable,f.doklaguntext,f.doklaguntable,f.datuenbabesatext,f.datuenbabesatable,f.norkebatzitext,f.norkebatzitable,f.besteak1text,f.besteak1table,f.besteak2text,f.besteak2table,f.besteak3text,f.besteak3table,f.kanalatext,f.kanalatable,f.azpisailatable
    //     FROM App:Espedientekudeaketa f
    //     WHERE f.udala=:udala
    // '
    // );
    // $query->setParameter( 'udala', $fitxa->getUdala() );


    /**
     * @return Espedientekudeaketa[] Returns an array of Espedientekudeaketa objects
     */

    public function findOneByUdala($udala)
    {
        return $this->createQueryBuilder('e')
            ->select('e.oharraktext,e.helburuatext,e.ebazpensinpli,e.arduraaitorpena,e.aurreikusi,e.arrunta,e.isiltasunadmin,e.norkeskatutext,e.norkeskatutable,e.dokumentazioatext,e.dokumentazioatable,e.kostuatext,e.kostuatable,e.araudiatext,e.araudiatable,e.prozeduratext,e.prozeduratable,e.doklaguntext,e.doklaguntable,e.datuenbabesatext,e.datuenbabesatable,e.norkebatzitext,e.norkebatzitable,e.besteak1text,e.besteak1table,e.besteak2text,e.besteak2table,e.besteak3text,e.besteak3table,e.kanalatext,e.kanalatable,e.azpisailatable')
            ->andWhere('e.udala = :udala')
            ->setParameter('udala', $udala)
//            ->orderBy('s.kodea', 'DESC')
            ->getQuery()
            ->getSingleResult()
        ;
    }

    // $query = $em->createQuery(
    //     /** @lang text */
    //     '
    //   SELECT f.oharraklabeleu,f.oharraklabeles,f.helburualabeleu,f.helburualabeles,f.ebazpensinplilabeleu,f.ebazpensinplilabeles,f.arduraaitorpenalabeleu,f.arduraaitorpenalabeles,f.aurreikusilabeleu,f.aurreikusilabeles,f.arruntalabeleu,f.arruntalabeles,f.isiltasunadminlabeleu,f.isiltasunadminlabeles,f.norkeskatulabeleu,f.norkeskatulabeles,f.dokumentazioalabeleu,f.dokumentazioalabeles,f.kostualabeleu,f.kostualabeles,f.araudialabeleu,f.araudialabeles,f.prozeduralabeleu,f.prozeduralabeles,f.doklagunlabeleu,f.doklagunlabeles,f.datuenbabesalabeleu,f.datuenbabesalabeles,f.norkebatzilabeleu,f.norkebatzilabeles,f.besteak1labeleu,f.besteak1labeles,f.besteak2labeleu,f.besteak2labeles,f.besteak3labeleu,f.besteak3labeles,f.kanalalabeleu,f.kanalalabeles,f.epealabeleu,f.epealabeles,f.doanlabeleu,f.doanlabeles,f.azpisailalabeleu,f.azpisailalabeles
    //     FROM App:Espedientekudeaketa f
    //     WHERE f.udala=:udala
    // '
    // );
    // $query->setParameter( 'udala', $fitxa->getUdala() );


    public function findLabelakByUdala($udala) {
        return $this->createQueryBuilder('e')
            ->select('e.oharraktext,e.helburuatext,e.ebazpensinpli,e.arduraaitorpena,e.aurreikusi,e.arrunta,e.isiltasunadmin,e.norkeskatutext,e.norkeskatutable,e.dokumentazioatext,e.dokumentazioatable,e.kostuatext,e.kostuatable,e.araudiatext,e.araudiatable,e.prozeduratext,e.prozeduratable,e.doklaguntext,e.doklaguntable,e.datuenbabesatext,e.datuenbabesatable,e.norkebatzitext,e.norkebatzitable,e.besteak1text,e.besteak1table,e.besteak2text,e.besteak2table,e.besteak3text,e.besteak3table,e.kanalatext,e.kanalatable,e.azpisailatable')
            ->andWhere('e.udala = :udala')
            ->setParameter('udala', $udala)
            ->getQuery()
            ->getSingleResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Espedientekudeaketa
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
