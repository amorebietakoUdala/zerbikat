<?php

namespace App\Controller;

use App\Repository\EremuakRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
 
    public function __construct(private EntityManagerInterface $em, private EremuakRepository $eremuakRepo)
    {        
    }
    /**
     * Index
     */
    #[Route(path: '/')]
    public function index(): Response
    {
        if ( $this->isGranted("ROLE_ADMIN") ) {
            return $this->redirectToRoute('admin_user_index');
        }
        return $this->redirectToRoute('fitxa_index');
    }

    #[Route(path: '/froga')]
    public function froga() {

        $eremuak2 = $this->eremuakRepo->findOneByUdala('48340');
        
        $query = $this->em->createQuery(
            /** @lang text */
                '
              SELECT f.oharraktext,f.helburuatext,f.ebazpensinpli,f.arduraaitorpena,f.aurreikusi,f.arrunta,f.isiltasunadmin,f.norkeskatutext,f.norkeskatutable,f.dokumentazioatext,f.dokumentazioatable,f.kostuatext,f.kostuatable,f.araudiatext,f.araudiatable,f.prozeduratext,f.prozeduratable,f.doklaguntext,f.doklaguntable,f.datuenbabesatext,f.datuenbabesatable,f.norkebatzitext,f.norkebatzitable,f.besteak1text,f.besteak1table,f.besteak2text,f.besteak2table,f.besteak3text,f.besteak3table,f.kanalatext,f.kanalatable,f.azpisailatable
                FROM App:Eremuak f
                WHERE f.udala = :udala
            '
            );
        $query->setParameter( 'udala', '48340' );
        $eremuak = $query->getSingleResult();
        dd($eremuak, $eremuak2, $query->getSQL());    
    }
    // Frogak Ugaitz
    #[Route(path: '/froga2')]
    public function froga2() {

        $eremuak2 = $this->eremuakRepo->findLabelakByUdala('48340');
        
        $query = $this->em->createQuery(
            /** @lang text */
            '
          SELECT f.oharraklabeleu,f.oharraklabeles,f.helburualabeleu,f.helburualabeles,f.ebazpensinplilabeleu,f.ebazpensinplilabeles,f.arduraaitorpenalabeleu,f.arduraaitorpenalabeles,f.aurreikusilabeleu,f.aurreikusilabeles,f.arruntalabeleu,f.arruntalabeles,f.isiltasunadminlabeleu,f.isiltasunadminlabeles,f.norkeskatulabeleu,f.norkeskatulabeles,f.dokumentazioalabeleu,f.dokumentazioalabeles,f.kostualabeleu,f.kostualabeles,f.araudialabeleu,f.araudialabeles,f.prozeduralabeleu,f.prozeduralabeles,f.doklagunlabeleu,f.doklagunlabeles,f.datuenbabesalabeleu,f.datuenbabesalabeles,f.norkebatzilabeleu,f.norkebatzilabeles,f.besteak1labeleu,f.besteak1labeles,f.besteak2labeleu,f.besteak2labeles,f.besteak3labeleu,f.besteak3labeles,f.kanalalabeleu,f.kanalalabeles,f.epealabeleu,f.epealabeles,f.doanlabeleu,f.doanlabeles,f.azpisailalabeleu,f.azpisailalabeles
            FROM App:Eremuak f
            WHERE f.udala = :udala
        '
        );
        $query->setParameter( 'udala', '48340' );
        $eremuak = $query->getSingleResult();
        dd($eremuak, $eremuak2, $query->getSQL());    
    }

    #[Route(path: '/froga3')]
    public function froga3() {

        $eremuak2 = $this->eremuakRepo->findLabelakBoolByUdalKodea('48340');
        
        $query = $this->em->createQuery(
            /** @lang text */
            '
        SELECT f.oharraktext,f.helburuatext,f.ebazpensinpli,f.arduraaitorpena,f.aurreikusi,f.arrunta,f.isiltasunadmin,f.norkeskatutext,f.norkeskatutable,f.dokumentazioatext,f.dokumentazioatable,f.kostuatext,f.kostuatable,f.araudiatext,f.araudiatable,f.prozeduratext,f.prozeduratable,f.doklaguntext,f.doklaguntable,f.datuenbabesatext,f.datuenbabesatable,f.norkebatzitext,f.norkebatzitable,f.besteak1text,f.besteak1table,f.besteak2text,f.besteak2table,f.besteak3text,f.besteak3table,f.kanalatext,f.kanalatable,f.azpisailatable
            FROM App:Eremuak f
            WHERE f.udala = :udala            
        '
    );
        $query->setParameter( 'udala', '48340' );
        $eremuak = $query->getSingleResult();
        dd($eremuak, $eremuak2, $query->getSQL());    
    }

    #[Route(path: '/froga4')]
    public function froga4() {

        $eremuak2 = $this->eremuakRepo->findLabelakByUdala('48340');
        
        $query = $this->em->createQuery(
            /** @lang text */
            '
        SELECT f.oharraklabeleu,f.oharraklabeles,f.helburualabeleu,f.helburualabeles,f.ebazpensinplilabeleu,f.ebazpensinplilabeles,f.arduraaitorpenalabeleu,f.arduraaitorpenalabeles,f.aurreikusilabeleu,f.aurreikusilabeles,f.arruntalabeleu,f.arruntalabeles,f.isiltasunadminlabeleu,f.isiltasunadminlabeles,f.norkeskatulabeleu,f.norkeskatulabeles,f.dokumentazioalabeleu,f.dokumentazioalabeles,f.kostualabeleu,f.kostualabeles,f.araudialabeleu,f.araudialabeles,f.prozeduralabeleu,f.prozeduralabeles,f.doklagunlabeleu,f.doklagunlabeles,f.datuenbabesalabeleu,f.datuenbabesalabeles,f.norkebatzilabeleu,f.norkebatzilabeles,f.besteak1labeleu,f.besteak1labeles,f.besteak2labeleu,f.besteak2labeles,f.besteak3labeleu,f.besteak3labeles,f.kanalalabeleu,f.kanalalabeles,f.epealabeleu,f.epealabeles,f.doanlabeleu,f.doanlabeles,f.azpisailalabeleu,f.azpisailalabeles
            FROM App:Eremuak f
            WHERE f.udala = :udala
        '
    );
        $query->setParameter( 'udala', '48340' );
        $eremuak = $query->getSingleResult();
        dd($eremuak, $eremuak2, $query->getSQL());    
    }

    #[Route(path: '/froga5')]
    public function froga5() {

        $eremuak2 = $this->eremuakRepo->findLabelakBoolByUdalKodea('48340');
        
        $query = $this->em->createQuery(
            /** @lang text */
            '
          SELECT f.oharraktext,f.helburuatext,f.ebazpensinpli,f.arduraaitorpena,f.aurreikusi,f.arrunta,f.isiltasunadmin,f.norkeskatutext,f.norkeskatutable,f.dokumentazioatext,f.dokumentazioatable,f.kostuatext,f.kostuatable,f.araudiatext,f.araudiatable,f.prozeduratext,f.prozeduratable,f.doklaguntext,f.doklaguntable,f.datuenbabesatext,f.datuenbabesatable,f.norkebatzitext,f.norkebatzitable,f.besteak1text,f.besteak1table,f.besteak2text,f.besteak2table,f.besteak3text,f.besteak3table,f.kanalatext,f.kanalatable,f.azpisailatable
            FROM App:Eremuak f
            WHERE f.udala = :udala            
        '
        );
        $query->setParameter( 'udala', '48340' );
        $eremuak = $query->getSingleResult();
        dd($eremuak, $eremuak2, $query->getSQL());    
    }

    #[Route(path: '/froga6')]
    public function froga6() {

        $eremuak2 = $this->eremuakRepo->findLabelakByUdala('48340');
        
        $query = $this->em->createQuery(
            /** @lang text */
            '
          SELECT f.oharraklabeleu,f.oharraklabeles,f.helburualabeleu,f.helburualabeles,f.ebazpensinplilabeleu,f.ebazpensinplilabeles,f.arduraaitorpenalabeleu,f.arduraaitorpenalabeles,f.aurreikusilabeleu,f.aurreikusilabeles,f.arruntalabeleu,f.arruntalabeles,f.isiltasunadminlabeleu,f.isiltasunadminlabeles,f.norkeskatulabeleu,f.norkeskatulabeles,f.dokumentazioalabeleu,f.dokumentazioalabeles,f.kostualabeleu,f.kostualabeles,f.araudialabeleu,f.araudialabeles,f.prozeduralabeleu,f.prozeduralabeles,f.doklagunlabeleu,f.doklagunlabeles,f.datuenbabesalabeleu,f.datuenbabesalabeles,f.norkebatzilabeleu,f.norkebatzilabeles,f.besteak1labeleu,f.besteak1labeles,f.besteak2labeleu,f.besteak2labeles,f.besteak3labeleu,f.besteak3labeles,f.kanalalabeleu,f.kanalalabeles,f.epealabeleu,f.epealabeles,f.doanlabeleu,f.doanlabeles,f.azpisailalabeleu,f.azpisailalabeles
            FROM App:Eremuak f
            WHERE f.udala = :udala
        '
        );
        $query->setParameter( 'udala', '48340' );
        $eremuak = $query->getSingleResult();
        dd($eremuak, $eremuak2, $query->getSQL());    
    }

    #[Route(path: '/froga7')]
    public function froga7() {

        $eremuak2 = $this->eremuakRepo->findLabelakByUdala('48340');
        
        $query = $this->em->createQuery(
            /** @lang text */
                '
              SELECT f.oharraklabeleu,f.oharraklabeles,f.helburualabeleu,f.helburualabeles,f.ebazpensinplilabeleu,f.ebazpensinplilabeles,f.arduraaitorpenalabeleu,f.arduraaitorpenalabeles,f.aurreikusilabeleu,f.aurreikusilabeles,f.arruntalabeleu,f.arruntalabeles,f.isiltasunadminlabeleu,f.isiltasunadminlabeles,f.norkeskatulabeleu,f.norkeskatulabeles,f.dokumentazioalabeleu,f.dokumentazioalabeles,f.kostualabeleu,f.kostualabeles,f.araudialabeleu,f.araudialabeles,f.prozeduralabeleu,f.prozeduralabeles,f.doklagunlabeleu,f.doklagunlabeles,f.datuenbabesalabeleu,f.datuenbabesalabeles,f.norkebatzilabeleu,f.norkebatzilabeles,f.besteak1labeleu,f.besteak1labeles,f.besteak2labeleu,f.besteak2labeles,f.besteak3labeleu,f.besteak3labeles,f.kanalalabeleu,f.kanalalabeles,f.epealabeleu,f.epealabeles,f.doanlabeleu,f.doanlabeles,f.azpisailalabeleu,f.azpisailalabeles
                FROM App:Eremuak f
                WHERE f.udala = :udala                
            '
            );
        $query->setParameter( 'udala', '48340' );
        $eremuak = $query->getSingleResult();
        dd($eremuak, $eremuak2, $query->getSQL());    
    }
}
