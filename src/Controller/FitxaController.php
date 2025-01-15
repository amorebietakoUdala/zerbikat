<?php

namespace App\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query;
use GuzzleHttp;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Familia;
use App\Entity\Fitxa;
use App\Entity\Fitxafamilia;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\FitxaAldaketa;
use App\Form\FitxafamiliaType;
use App\Form\FitxanewType;
use App\Form\FitxaType;
use App\Repository\FamiliaRepository;
use App\Repository\FitxaRepository;
use App\Repository\KanalmotaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Qipsius\TCPDFBundle\Controller\TCPDFController;

/**
 * Fitxa controller.
 *
 * @Route("/{_locale}/fitxa")
 */
class FitxaController extends AbstractController
{

    private $logger;
    private $tcpdfController;
    private $zzoo_aplikazioaren_API_url;
    private $repo;
    private $em;
    private $familiaRepo;
    private $kanalmotaRepo;

    public function __construct(EntityManagerInterface $em, FitxaRepository $repo, FamiliaRepository $familiaRepo, KanalmotaRepository $kanalmotaRepo, LoggerInterface $logger, TCPDFController $tcpdfController, $zzoo_aplikazioaren_API_url)
    {
        $this->em = $em;
        $this->repo = $repo;
        $this->familiaRepo = $familiaRepo;
        $this->kanalmotaRepo = $kanalmotaRepo;
        $this->logger = $logger;
        $this->tcpdfController = $tcpdfController;
        $this->zzoo_aplikazioaren_API_url = $zzoo_aplikazioaren_API_url;
    }

    /**
     * Lists all Fitxa entities.
     *
     * @Route("/", name="fitxa_index", methods={"GET"})
     */
    public function index()
    {
        if ( $this->isGranted( 'ROLE_USER' ) ) {
            $fitxas = $this->repo->findAzpisailakOrderedBySailakAzpisailak();

            $deleteForms = [];
            /** @var Fitxa $fitxa */
            foreach ( $fitxas as $fitxa ) {
                $deleteForms[ $fitxa->getId() ] = $this->createDeleteForm( $fitxa )->createView();
            }

            return $this->render(
                'fitxa/index.html.twig',
                ['deleteforms' => $deleteForms, 'fitxas'      => $fitxas]
            );
        } else {
            return $this->redirectToRoute( 'fos_user_security_login' );
        }
    }

    /**
     * Creates a new Fitxa entity.
     *
     * @Route("/new", name="fitxa_new", methods={"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new( Request $request )
    {
        if ( $this->isGranted( 'ROLE_USER' ) ) {
            /** @var Fitxa $fitxa */
            $fitxa = new Fitxa();
            $fitxa->setUdala( $this->getUser()->getUdala() );
            $form = $this->createForm( FitxanewType::class, $fitxa );
            $form->handleRequest( $request );

            if ( $form->isSubmitted() && $form->isValid() ) {
                $fitxa->setCreatedAt( new \DateTime() );
                $fitxa->setNorkSortua($this->getUser());
                $this->em->persist( $fitxa );
                $this->em->flush();
                $this->saveFitxaAldaketa($fitxa, 'Sortua');

                return $this->redirectToRoute( 'fitxa_edit', ['id' => $fitxa->getId()] );
            } else {
                $form->getData()->setUdala( $this->getUser()->getUdala() );
                $form->setData( $form->getData() );
            }


            return $this->render(
                'fitxa/new.html.twig',
                ['fitxa' => $fitxa, 'form'  => $form->createView()]
            );
        } else {
            return $this->redirectToRoute( 'fos_user_security_login' );
        }
    }

    /**
     * Finds and displays a Fitxa entity.
     *
     * @Route("/{id}", name="fitxa_show", methods={"GET"})
     * @param Fitxa $fitxa
     * @return Response
     */
    public function show( Fitxa $fitxa ): Response
    {
        $deleteForm = $this->createDeleteForm( $fitxa );

        $kanalmotak = $this->kanalmotaRepo->findAll();

        $kostuZerrenda = [];
        foreach ( $fitxa->getKostuak() as $kostu ) {
            $client = new GuzzleHttp\Client();
            $proba = $client->request( 'GET', $this->zzoo_aplikazioaren_API_url . '/zerga/' . $kostu->getKostua() . '?format=json' );
            $fitxaKostua = (string)$proba->getBody();
            $array = json_decode( $fitxaKostua, true );
            $kostuZerrenda[] = $array;
        }

        /** @var Query $query */
        $query = $this->em->createQuery(
        /** @lang text */
            '
          SELECT f.oharraktext,f.helburuatext,f.ebazpensinpli,f.arduraaitorpena,f.aurreikusi,f.arrunta,f.isiltasunadmin,f.norkeskatutext,f.norkeskatutable,f.dokumentazioatext,f.dokumentazioatable,f.kostuatext,f.kostuatable,f.araudiatext,f.araudiatable,f.prozeduratext,f.prozeduratable,f.doklaguntext,f.doklaguntable,f.datuenbabesatext,f.datuenbabesatable,f.norkebatzitext,f.norkebatzitable,f.besteak1text,f.besteak1table,f.besteak2text,f.besteak2table,f.besteak3text,f.besteak3table,f.kanalatext,f.kanalatable,f.azpisailatable
            FROM App:Eremuak f
            WHERE f.udala = :udala
        '
        );
        $query->setParameter( 'udala', $fitxa->getUdala() );
        $eremuak = $query->getSingleResult();

        $query = $this->em->createQuery(
        /** @lang text */
            '
          SELECT f.oharraklabeleu,f.oharraklabeles,f.helburualabeleu,f.helburualabeles,f.ebazpensinplilabeleu,f.ebazpensinplilabeles,f.arduraaitorpenalabeleu,f.arduraaitorpenalabeles,f.aurreikusilabeleu,f.aurreikusilabeles,f.arruntalabeleu,f.arruntalabeles,f.isiltasunadminlabeleu,f.isiltasunadminlabeles,f.norkeskatulabeleu,f.norkeskatulabeles,f.dokumentazioalabeleu,f.dokumentazioalabeles,f.kostualabeleu,f.kostualabeles,f.araudialabeleu,f.araudialabeles,f.prozeduralabeleu,f.prozeduralabeles,f.doklagunlabeleu,f.doklagunlabeles,f.datuenbabesalabeleu,f.datuenbabesalabeles,f.norkebatzilabeleu,f.norkebatzilabeles,f.besteak1labeleu,f.besteak1labeles,f.besteak2labeleu,f.besteak2labeles,f.besteak3labeleu,f.besteak3labeles,f.kanalalabeleu,f.kanalalabeles,f.epealabeleu,f.epealabeles,f.doanlabeleu,f.doanlabeles,f.azpisailalabeleu,f.azpisailalabeles
            FROM App:Eremuak f
            WHERE f.udala = :udala
        '
        );
        $query->setParameter( 'udala', $fitxa->getUdala() );
        $labelak = $query->getSingleResult();

        return $this->render(
            'fitxa/show.html.twig',
            ['fitxa'         => $fitxa, 'kanalmotak'    => $kanalmotak, 'delete_form'   => $deleteForm->createView(), 'eremuak'       => $eremuak, 'labelak'       => $labelak, 'kostuZerrenda' => $kostuZerrenda]
        );
    }

    /**
     * Fitxa bakoitzaren dokumentazio laguntzailearen pdf bat sortu
     * @Route("/pdf/all/doklagun/{id}", name="doklagun_guztiak_pdf", methods={"GET"})
     */
    public function pdfAllDokLagn ( $id = null ) {
	$user = $this->getUser();
	$roles = $user->getRoles();
	$isRoleSuperAdmin = in_array("ROLE_SUPER_ADMIN", $roles);
	$udala = ( $isRoleSuperAdmin ? $id : $user->getUdala());
	$fitxak = $this->repo->findBy([
	    'udala' => $udala,
//	    'espedientekodea' => 'IL01400'
	    ],
	    ['espedientekodea' => 'ASC'] // order
	);
//	dump($udala);die;
	$pdf = $this->__generateAllFitxaHTML($fitxak,'fitxa/pdf_dokumentazioa.html.twig');

        $filename = "izapideen-liburua";
	return $pdf->Output( $filename . ".pdf", 'I' ); // This will output the PDF as a response directly
    }

    /**
     * Fitxa guztiekin pdf bat sortu
     * @Route("/pdf/all/{id}", name="fitxa_guztiak_pdf", methods={"GET"})
     */
    public function pdfAll ( $id = null ) {
	$user = $this->getUser();
	$roles = $user->getRoles();
	$isRoleSuperAdmin = in_array("ROLE_SUPER_ADMIN", $roles);
	$udala = ( $isRoleSuperAdmin ? $id : $user->getUdala());
	$fitxak = $this->repo->findBy([
	    'udala' => $udala,
//	    'espedientekodea' => 'IL01400'
	    ],
	    ['espedientekodea' => 'ASC'] // order
	);
//	dump($udala);die;
	$pdf = $this->__generateAllFitxaHTML($fitxak);

        $filename = "izapideen-liburua";
	return $pdf->Output( $filename . ".pdf", 'I' ); // This will output the PDF as a response directly
    }

    /**
     * Generates the complete HTML codea of all Fitxa in the array passed as argument
     * 
     * @param Array $fitxak
     */
    
    private function __generateAllFitxaHTML( Array $fitxak, $plantilla = 'fitxa/pdf.html.twig' ) {
	$udala = $fitxak[0]->getUdala();
	$kanalmotak = $this->kanalmotaRepo->findAll();
        $query = $this->em->createQuery(
            /** @lang text */
            '
          SELECT f.oharraktext,f.helburuatext,f.ebazpensinpli,f.arduraaitorpena,f.aurreikusi,f.arrunta,f.isiltasunadmin,f.norkeskatutext,f.norkeskatutable,f.dokumentazioatext,f.dokumentazioatable,f.kostuatext,f.kostuatable,f.araudiatext,f.araudiatable,f.prozeduratext,f.prozeduratable,f.doklaguntext,f.doklaguntable,f.datuenbabesatext,f.datuenbabesatable,f.norkebatzitext,f.norkebatzitable,f.besteak1text,f.besteak1table,f.besteak2text,f.besteak2table,f.besteak3text,f.besteak3table,f.kanalatext,f.kanalatable,f.azpisailatable
            FROM App:Eremuak f
            WHERE f.udala = :udala            
        '
        );
        $query->setParameter( 'udala', $udala );
        $eremuak = $query->getSingleResult();

	$query = $this->em->createQuery(
            /** @lang text */
            '
          SELECT f.oharraklabeleu,f.oharraklabeles,f.helburualabeleu,f.helburualabeles,f.ebazpensinplilabeleu,f.ebazpensinplilabeles,f.arduraaitorpenalabeleu,f.arduraaitorpenalabeles,f.aurreikusilabeleu,f.aurreikusilabeles,f.arruntalabeleu,f.arruntalabeles,f.isiltasunadminlabeleu,f.isiltasunadminlabeles,f.norkeskatulabeleu,f.norkeskatulabeles,f.dokumentazioalabeleu,f.dokumentazioalabeles,f.kostualabeleu,f.kostualabeles,f.araudialabeleu,f.araudialabeles,f.prozeduralabeleu,f.prozeduralabeles,f.doklagunlabeleu,f.doklagunlabeles,f.datuenbabesalabeleu,f.datuenbabesalabeles,f.norkebatzilabeleu,f.norkebatzilabeles,f.besteak1labeleu,f.besteak1labeles,f.besteak2labeleu,f.besteak2labeles,f.besteak3labeleu,f.besteak3labeles,f.kanalalabeleu,f.kanalalabeles,f.epealabeleu,f.epealabeles,f.doanlabeleu,f.doanlabeles,f.azpisailalabeleu,f.azpisailalabeles
            FROM App:Eremuak f
            WHERE f.udala = :udala
        '
        );
        $query->setParameter( 'udala', $udala );
        $labelak = $query->getSingleResult();

        $pdf = $this->tcpdfController->create('vertical',PDF_UNIT, PDF_PAGE_FORMAT,true,'UTF-8', false);
        $pdf->SetAuthor( $this->getUser()->getUdala() );
        $pdf->SetTitle( ( "Izapideen Liburua" ) );
        $pdf->SetSubject( "Libro de procedimientos" );
        $pdf->setFontSubsetting( true );
	$pdf->SetFont( 'helvetica', '', 11, '', true );

//	$full_html = '';
	foreach ($fitxak as $fitxa ) {
	    $this->logger->debug($fitxa->getEspedienteKodea());
	    $kostuZerrenda = [];
	    foreach ( $fitxa->getKostuak() as $kostu ) {
		$this->logger->info($kostu->getId());
		$client = new GuzzleHttp\Client();

    //            $proba = $client->request( 'GET', 'http://zergaordenantzak.dev/app_dev.php/api/azpiatalas/'.$kostu->getKostua().'?format=json' );
		$proba = $client->request( 'GET', $this->zzoo_aplikazioaren_API_url . '/zerga/' . $kostu->getKostua() . '?format=json' );

		$fitxaKostua = (string)$proba->getBody();
		$array = json_decode( $fitxaKostua, true );
		$kostuZerrenda[] = $array;
	    }
	    // Debug only:
//	    return $this->render(
	    $html = $this->render(
		$plantilla,
		[
      'fitxa'         => $fitxa,
      'kanalmotak'    => $kanalmotak,
      //		    'delete_form'   => $deleteForm->createView(),
      'eremuak'       => $eremuak,
      'labelak'       => $labelak,
      'kostuZerrenda' => $kostuZerrenda,
  ]
	    );
	    $pdf->AddPage();
	    $pdf->writeHTML($html->getContent(), true, false, false, false, '');

//	    $full_html = $full_html.$html->getContent();
	}
//	$full_html = '<html><head><meta charset="utf-8"></head><body>'.$full_html.'</body></html>';
	return $pdf;
//	return $full_html;
    }

     /**
     * Izapide guztien PDF bat sortu.
     *
     * @param string $html
     */
    private function __pdfaSortu ($html) {
        $pdf = $this->tcpdfController->create(
            'vertical',
            PDF_UNIT,
            PDF_PAGE_FORMAT,
            true,
            'UTF-8',
            false
        );
        $pdf->SetAuthor( $this->getUser()->getUdala() );
        $pdf->SetTitle( ( "Izapideen Liburua" ) );
        $pdf->SetSubject( "Libro de procedimientos" );
        $pdf->setFontSubsetting( false );
       $pdf->SetFont( 'helvetica', '', 11, '', true );
        $pdf->AddPage();
	$pdf->writeHTML($html, true, false, false, false, '');
	return $pdf;
    }

        /**
     * Finds and displays a Fitxa entity.
     *
     * @Route("/pdf/{id}", name="fitxa_pdf", methods={"GET"})
     * @param Fitxa $fitxa
     */
    public function pdf( Fitxa $fitxa )
    {
        $deleteForm = $this->createDeleteForm( $fitxa );

        $kanalmotak = $this->kanalmotaRepo->findAll();

        /** @var Query $query */
        $query = $this->em->createQuery(
            /** @lang text */
            '
          SELECT f.oharraktext,f.helburuatext,f.ebazpensinpli,f.arduraaitorpena,f.aurreikusi,f.arrunta,f.isiltasunadmin,f.norkeskatutext,f.norkeskatutable,f.dokumentazioatext,f.dokumentazioatable,f.kostuatext,f.kostuatable,f.araudiatext,f.araudiatable,f.prozeduratext,f.prozeduratable,f.doklaguntext,f.doklaguntable,f.datuenbabesatext,f.datuenbabesatable,f.norkebatzitext,f.norkebatzitable,f.besteak1text,f.besteak1table,f.besteak2text,f.besteak2table,f.besteak3text,f.besteak3table,f.kanalatext,f.kanalatable,f.azpisailatable
            FROM App:Eremuak f
            WHERE f.udala = :udala            
        '
        );
        $query->setParameter( 'udala', $fitxa->getUdala() );
        $eremuak = $query->getSingleResult();

        $query = $this->em->createQuery(
            /** @lang text */
            '
          SELECT f.oharraklabeleu,f.oharraklabeles,f.helburualabeleu,f.helburualabeles,f.ebazpensinplilabeleu,f.ebazpensinplilabeles,f.arduraaitorpenalabeleu,f.arduraaitorpenalabeles,f.aurreikusilabeleu,f.aurreikusilabeles,f.arruntalabeleu,f.arruntalabeles,f.isiltasunadminlabeleu,f.isiltasunadminlabeles,f.norkeskatulabeleu,f.norkeskatulabeles,f.dokumentazioalabeleu,f.dokumentazioalabeles,f.kostualabeleu,f.kostualabeles,f.araudialabeleu,f.araudialabeles,f.prozeduralabeleu,f.prozeduralabeles,f.doklagunlabeleu,f.doklagunlabeles,f.datuenbabesalabeleu,f.datuenbabesalabeles,f.norkebatzilabeleu,f.norkebatzilabeles,f.besteak1labeleu,f.besteak1labeles,f.besteak2labeleu,f.besteak2labeles,f.besteak3labeleu,f.besteak3labeles,f.kanalalabeleu,f.kanalalabeles,f.epealabeleu,f.epealabeles,f.doanlabeleu,f.doanlabeles,f.azpisailalabeleu,f.azpisailalabeles
            FROM App:Eremuak f
            WHERE f.udala = :udala
        '
        );
        $query->setParameter( 'udala', $fitxa->getUdala() );
        $labelak = $query->getSingleResult();

        $kostuZerrenda = [];
        foreach ( $fitxa->getKostuak() as $kostu ) {
            $client = new GuzzleHttp\Client();

//            $proba = $client->request( 'GET', 'http://zergaordenantzak.dev/app_dev.php/api/azpiatalas/'.$kostu->getKostua().'?format=json' );
            $proba = $client->request( 'GET', $this->zzoo_aplikazioaren_API_url . '/zerga/' . $kostu->getKostua() . '?format=json' );

            $fitxaKostua = (string)$proba->getBody();
            $array = json_decode( $fitxaKostua, true );
            $kostuZerrenda[] = $array;
        }

        // Debug only:
        //return $this->render(
        $html = $this->render(
            'fitxa/pdf.html.twig',
            ['fitxa'         => $fitxa, 'kanalmotak'    => $kanalmotak, 'delete_form'   => $deleteForm->createView(), 'eremuak'       => $eremuak, 'labelak'       => $labelak, 'kostuZerrenda' => $kostuZerrenda]
        );

        $pdf = $this->tcpdfController->create(
            'vertical',
            PDF_UNIT,
            PDF_PAGE_FORMAT,
            true,
            'UTF-8',
            false
        );
        $pdf->SetAuthor( $this->getUser()->getUdala() );
//        $pdf->SetTitle(('Our Code World Title'));
        $pdf->SetTitle( ( $fitxa->getDeskribapenaeu() ) );
        $pdf->SetSubject( $fitxa->getDeskribapenaes() );
        $pdf->setFontSubsetting( true );
        $pdf->SetFont( 'helvetica', '', 11, '', true );
        //$pdf->SetMargins(20,20,40, true);
        $pdf->AddPage();

        $filename = $fitxa->getEspedientekodea() . "." . $fitxa->getDeskribapenaeu();

        $pdf->writeHTMLCell(
            $w = 0,
            $h = 0,
            $x = '',
            $y = '',
            $html->getContent(),
            $border = 0,
            $ln = 1,
            $fill = 0,
            $reseth = true,
            $align = '',
            $autopadding = true
        );
        $pdf->Output( $filename . ".pdf", 'I' ); // This will output the PDF as a response directly
    }

    /**
     * Displays a form to edit an existing Fitxa entity.
     *
     * @Route("/{id}/edit", name="fitxa_edit", methods={"GET", "POST"})
     * @param Request $request
     * @param Fitxa   $fitxa
     * @return RedirectResponse|Response
     */
    public function edit( Request $request, Fitxa $fitxa )
    {
        if ( ( ( $this->isGranted( 'ROLE_USER' ) ) && ( $fitxa->getUdala() == $this->getUser()->getUdala() ) )
            || ( $this->isGranted( 'ROLE_SUPER_ADMIN' ) )
        ) {
            $deleteForm = $this->createDeleteForm( $fitxa );

            $editForm = $this->createForm(
                FitxaType::class,
                $fitxa,
                ['user' => $this->getUser(), 'api_url' => $this->zzoo_aplikazioaren_API_url]
            );

            // Create an ArrayCollection of the current Kostuak objects in the database
            $originalKostuak = new ArrayCollection();
            foreach ( $fitxa->getKostuak() as $kostu ) {
                $originalKostuak->add( $kostu );
            }
            // Create an ArrayCollection of the current Araudiak objects in the database
            $originalAraudiak = new ArrayCollection();
            foreach ( $fitxa->getAraudiak() as $araudi ) {
                $originalAraudiak->add( $araudi );
            }
            // Create an ArrayCollection of the current Prozedurak objects in the database
            $originalProzedurak = new ArrayCollection();
            foreach ( $fitxa->getProzedurak() as $prozedura ) {
                $originalProzedurak->add( $prozedura );
            }

            $editForm->handleRequest( $request );

            if ( $editForm->isSubmitted() && $editForm->isValid() ) {
                foreach ( $originalKostuak as $kostu ) {
                    if ( false === $fitxa->getKostuak()->contains( $kostu ) ) {
                        $kostu->setFitxa( null );
                        $this->em->remove( $kostu );
                        $this->em->persist( $fitxa );
                    }
                }
                foreach ( $originalAraudiak as $araudi ) {
                    if ( false === $fitxa->getAraudiak()->contains( $araudi ) ) {
                        $araudi->setFitxa( null );
                        $this->em->remove( $araudi );
                        $this->em->persist( $fitxa );
                    }
                }
                foreach ( $originalProzedurak as $prozedura ) {
                    if ( false === $fitxa->getProzedurak()->contains( $prozedura ) ) {
                        $prozedura->setFitxa( null );
                        $this->em->remove( $prozedura );
                        $this->em->persist( $fitxa );
                    }
                }
                $fitxa->setUpdatedAt( new \DateTime() );
                $this->em->persist( $fitxa );
                $this->em->flush();
                $this->saveFitxaAldaketa($fitxa, 'Aldatua');

                return $this->redirectToRoute( 'fitxa_edit', ['id' => $fitxa->getId()] );
            }

            /** @var Query $query */
            $query = $this->em->createQuery(
            /** @lang text */
                '
              SELECT f.oharraktext,f.helburuatext,f.ebazpensinpli,f.arduraaitorpena,f.aurreikusi,f.arrunta,f.isiltasunadmin,f.norkeskatutext,f.norkeskatutable,f.dokumentazioatext,f.dokumentazioatable,f.kostuatext,f.kostuatable,f.araudiatext,f.araudiatable,f.prozeduratext,f.prozeduratable,f.doklaguntext,f.doklaguntable,f.datuenbabesatext,f.datuenbabesatable,f.norkebatzitext,f.norkebatzitable,f.besteak1text,f.besteak1table,f.besteak2text,f.besteak2table,f.besteak3text,f.besteak3table,f.kanalatext,f.kanalatable,f.azpisailatable
                FROM App:Eremuak f
                WHERE f.udala = :udala                
            '
            );
            $query->setParameter( 'udala', $fitxa->getUdala() );
            $eremuak = $query->getSingleResult();

            $query = $this->em->createQuery(
            /** @lang text */
                '
              SELECT f.oharraklabeleu,f.oharraklabeles,f.helburualabeleu,f.helburualabeles,f.ebazpensinplilabeleu,f.ebazpensinplilabeles,f.arduraaitorpenalabeleu,f.arduraaitorpenalabeles,f.aurreikusilabeleu,f.aurreikusilabeles,f.arruntalabeleu,f.arruntalabeles,f.isiltasunadminlabeleu,f.isiltasunadminlabeles,f.norkeskatulabeleu,f.norkeskatulabeles,f.dokumentazioalabeleu,f.dokumentazioalabeles,f.kostualabeleu,f.kostualabeles,f.araudialabeleu,f.araudialabeles,f.prozeduralabeleu,f.prozeduralabeles,f.doklagunlabeleu,f.doklagunlabeles,f.datuenbabesalabeleu,f.datuenbabesalabeles,f.norkebatzilabeleu,f.norkebatzilabeles,f.besteak1labeleu,f.besteak1labeles,f.besteak2labeleu,f.besteak2labeles,f.besteak3labeleu,f.besteak3labeles,f.kanalalabeleu,f.kanalalabeles,f.epealabeleu,f.epealabeles,f.doanlabeleu,f.doanlabeles,f.azpisailalabeleu,f.azpisailalabeles
                FROM App:Eremuak f
                WHERE f.udala = :udala                
            '
            );
            $query->setParameter( 'udala', $fitxa->getUdala() );
            $labelak = $query->getSingleResult();

            $fitxafamilium = new Fitxafamilia();
            $fitxafamilium->setFitxa( $fitxa );
            $fitxafamilium->setUdala( $this->getUser()->getUdala() );
            $form = $this->createForm(
                FitxafamiliaType::class,
                $fitxafamilium,
                [
                    'action' => $this->generateUrl( 'fitxafamilia_newfromfitxa' ),
                ]
            );

            $familiak = $this->familiaRepo->findBy(
                ['udala'  => $fitxa->getUdala(), 'parent' => null],
                ['ordena' => 'ASC']
            );

            return $this->render(
                'fitxa/edit.html.twig',
                ['fitxa'            => $fitxa, 'udala'            => $this->getUser()->getUdala() != null ? $this->getUser()->getUdala()->getId() : null, 'udal'             => $this->getUser()->getUdala(), 'edit_form'        => $editForm->createView(), 'delete_form'      => $deleteForm->createView(), 'formfitxafamilia' => $form->createView(), 'eremuak'          => $eremuak, 'labelak'          => $labelak, 'familiak'         => $familiak]
            );
        } else {
            return $this->redirectToRoute( 'backend_errorea' );
        }
    }

    /**
     * Deletes a Fitxa entity.
     *
     * @Route("/{id}/del", name="fitxa_delete", methods={"DELETE"})
     * @param Request $request
     * @param Fitxa   $fitxa
     * @return RedirectResponse
     */
    public function delete( Request $request, Fitxa $fitxa ): RedirectResponse
    {

        //udala egokia den eta admin baimena duen egiaztatu
        if ( ( ( $this->isGranted( 'ROLE_ADMIN' ) ) && ( $fitxa->getUdala() == $this->getUser()->getUdala() ) )
            || ( $this->isGranted( 'ROLE_SUPER_ADMIN' ) )
        ) {
            $form = $this->createDeleteForm( $fitxa );
            $form->handleRequest( $request );
            if ( $form->isSubmitted() ) {
                $this->em->remove( $fitxa );
                $this->saveFitxaAldaketa($fitxa, 'Ezabatua');
            }

            return $this->redirectToRoute( 'fitxa_index' );

        } else {
            //baimenik ez
            return $this->redirectToRoute( 'backend_errorea' );
        }
    }

    /**
     * Creates a form to delete a Fitxa entity.
     *
     * @param Fitxa $fitxa The Fitxa entity
     *
     * @return Form The form
     */
    private function createDeleteForm( Fitxa $fitxa )
    {
        return $this->createFormBuilder()
                    ->setAction( $this->generateUrl( 'fitxa_delete', ['id' => $fitxa->getId()] ) )
                    ->setMethod( Request::METHOD_DELETE )
                    ->getForm();
    }

    private function createfamiliaDeleteForm( Familia $familia )
    {
        return $this->createFormBuilder()
                    ->setAction( $this->generateUrl( 'familia_delete', ['id' => $familia->getId()] ) )
                    ->setMethod( Request::METHOD_DELETE )
                    ->getForm();
    }

    private function saveFitxaAldaketa($fitxa, $aldaketaMota) {
        $fitxaAldaketa = new FitxaAldaketa();
        $fitxaAldaketa->setNork($this->getUser());
        $fitxaAldaketa->setNoiz(new \DateTime());
        $fitxaAldaketa->setFitxaId($fitxa->getId());
        $fitxaAldaketa->setFitxaKodea($fitxa->getEspedientekodea());
        $fitxaAldaketa->setAldaketaMota($aldaketaMota);
        $this->em->persist( $fitxaAldaketa );
        $this->em->flush();

    }

}
