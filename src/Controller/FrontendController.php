<?php

namespace App\Controller;

use GuzzleHttp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Fitxa;
use App\Repository\EremuakRepository;
use App\Repository\FamiliaRepository;
use App\Repository\FitxaRepository;
use App\Repository\KanalmotaRepository;
use App\Repository\SailaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Qipsius\TCPDFBundle\Controller\TCPDFController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;

class FrontendController extends AbstractController
{

    private $tcpdfController;
    private $fitxaRepo;
    private $familiaRepo;
    private $sailaRepo;
    private $kanalmotaRepo;
    private $eremuakRepo;
    private $zzoo_aplikazioaren_API_url;
    private $pdfPath;
    
    public function __construct(
        TCPDFController $tcpdfController,
        FitxaRepository $fitxaRepo,
        FamiliaRepository $familiaRepo,
        SailaRepository $sailaRepo,
        KanalmotaRepository $kanalmotaRepo,
        EremuakRepository $eremuakRepo,
        string $zzoo_aplikazioaren_API_url,
        string $pdfPath
    )
    {
        $this->tcpdfController = $tcpdfController;
        $this->fitxaRepo = $fitxaRepo;
        $this->familiaRepo = $familiaRepo;
        $this->sailaRepo = $sailaRepo;
        $this->kanalmotaRepo = $kanalmotaRepo;
        $this->eremuakRepo = $eremuakRepo;
        $this->zzoo_aplikazioaren_API_url = $zzoo_aplikazioaren_API_url;
        $this->pdfPath = $pdfPath;
    }

    /**
     * @Route("/{udala}/{_locale}/", name="frontend_fitxa_index",
     *         requirements={
     *           "_locale": "eu|es",
     *           "udala": "\d+"
     *     }
     * )
     */
    public function index ( int $udala, Request $request ): Response
    {
        $familia = $request->get('familia') ?? null;
        $azpisaila = $request->get('azpisaila') ?? null ;

        $fitxak = $this->fitxaRepo->findPublicByUdalaAndAzpisaila($udala, $azpisaila);
        $familiak = $this->familiaRepo->findByUdalaAndParentAndFamiliaId($udala, $request->getLocale(), null, $familia);
        $sailak = $this->sailaRepo->findByUdalaAndAzpisaila($udala, $azpisaila);

        return $this->render(
            'frontend\index.html.twig',
            ['fitxak'   => $fitxak, 'familiak' => $familiak, 'sailak'   => $sailak, 'udala'    => $udala]
        );
    }


    /**
     * Finds and displays a Fitxa entity.
     *
     * @Route("/{udala}/{_locale}/{id}", name="frontend_fitxa_show", requirements={"_locale"="eu|es", "udala"="\d+"}, methods={"GET"})
     */
    public function show ( Fitxa $fitxa, int $udala ): Response
    {
        $kanalmotak = $this->kanalmotaRepo->findAll();
        $eremuak = $this->eremuakRepo->findOneByUdalKodea($udala);
        $labelak = $this->eremuakRepo->findLabelakByUdalKodea($udala);

        $kostuZerrenda = [];
        foreach ( $fitxa->getKostuak() as $kostu ) {
            $client = new GuzzleHttp\Client();
            $proba  = $client->request( 'GET', $this->zzoo_aplikazioaren_API_url . '/zerga/' . $kostu->getKostua() . '?format=json' );

            $fitxaKostua     = (string)$proba->getBody();
            $array           = json_decode( $fitxaKostua, true );
            $kostuZerrenda[] = $array;
        }

        return $this->render(
            'frontend/show.html.twig',
            ['fitxa'         => $fitxa, 'kanalmotak'    => $kanalmotak, 'eremuak'       => $eremuak, 'labelak'       => $labelak, 'udala'         => $udala, 'kostuZerrenda' => $kostuZerrenda]
        );
    }

    /**
     * Finds and displays a Fitxa entity.
     *
     * @Route("/{udala}/{_locale}/pdf/{id}/doklagun", name="frontend_pdf_doklagun", methods={"GET"})
     */
    public function pdfDocLagun ( Fitxa $fitxa, $udala )
    {
        $eremuak = $this->eremuakRepo->findOneByUdalKodea($udala);
        $labelak = $this->eremuakRepo->findLabelakByUdalKodea($udala);
        $html = $this->render(
            'frontend/pdf_dokumentazioa.html.twig',
            ['fitxa'         => $fitxa, 'eremuak'       => $eremuak, 'labelak'       => $labelak, 'udala'         => $udala]
        );

        $pdf = $this->tcpdfController->create(
            'vertical',
            PDF_UNIT,
            PDF_PAGE_FORMAT,
            true,
            'UTF-8',
            false
        );
        $pdf->SetAuthor( $udala );
        $pdf->SetTitle( ( $fitxa->getDeskribapenaeu() ) );
        $pdf->SetSubject( $fitxa->getDeskribapenaes() );
        $pdf->setFontSubsetting( true );
        $pdf->SetFont( 'helvetica', '', 11, '', true );
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
        $this->createPDF($html, $udala, $fitxa);
        $filename = $fitxa->getEspedientekodea() . "." . $fitxa->getDeskribapenaeu();
        return new BinaryFileResponse($this->pdfPath.'/'.$filename.'.pdf');

    }

    /**
     * Finds and displays a Fitxa entity.
     *
     * @Route("/{udala}/{_locale}/pdf/{id}", name="frontend_fitxa_pdf", methods={"GET"})
     */
    public function pdf ( Fitxa $fitxa, $udala )
    {
        $kanalmotak = $this->kanalmotaRepo->findAll();
        $eremuak = $this->eremuakRepo->findOneByUdalKodea($udala);
        $labelak = $this->eremuakRepo->findLabelakByUdalKodea($udala);

        $kostuZerrenda = [];
        foreach ( $fitxa->getKostuak() as $kostu ) {
            $client = new GuzzleHttp\Client();
            $proba  = $client->request( 'GET', $this->zzoo_aplikazioaren_API_url . '/zerga/' . $kostu->getKostua() . '?format=json' );

            $fitxaKostua     = (string)$proba->getBody();
            $array           = json_decode( $fitxaKostua, true );
            $kostuZerrenda[] = $array;
        }

        $html = $this->render(
            'frontend/pdf.html.twig',
            ['fitxa'         => $fitxa, 'kanalmotak'    => $kanalmotak, 'eremuak'       => $eremuak, 'labelak'       => $labelak, 'udala'         => $udala, 'kostuZerrenda' => $kostuZerrenda]
        );
        $this->createPDF($html, $udala, $fitxa);
        $filename = $fitxa->getEspedientekodea() . "." . $fitxa->getDeskribapenaeu();
        return new BinaryFileResponse($this->pdfPath.'/'.$filename.'.pdf');
    }


    /**
     * Finds and displays a Fitxa entity.
     *
     * @Route("/{udala}/{_locale}/pdfelebi/{id}", name="frontend_fitxa_pdfelebi", methods={"GET"})
     */
    public function pdfelebi ( Fitxa $fitxa, $udala )
    {

        $kanalmotak = $this->kanalmotaRepo->findAll();
        $eremuak = $this->eremuakRepo->findOneByUdalKodea($udala);
        $labelak = $this->eremuakRepo->findLabelakByUdalKodea($udala);

        $kostuZerrenda = [];
        foreach ( $fitxa->getKostuak() as $kostu ) {
            $client = new GuzzleHttp\Client();

//            $proba = $client->request( 'GET', 'http://zergaordenantzak.dev/app_dev.php/api/azpiatalas/'.$kostu->getKostua().'?format=json' );
            $proba = $client->request( 'GET', $this->zzoo_aplikazioaren_API_url . '/zerga/' . $kostu->getKostua() . '?format=json' );

            $fitxaKostua     = (string)$proba->getBody();
            $array           = json_decode( $fitxaKostua, true );
            $kostuZerrenda[] = $array;
        }
        $html = $this->render(
            'frontend/pdfelebi.html.twig',
            [
                'fitxa'         => $fitxa, 
                'kanalmotak'    => $kanalmotak, 
                'eremuak'       => $eremuak, 
                'labelak'       => $labelak, 
                'udala'         => $udala, 
                'kostuZerrenda' => $kostuZerrenda
            ]
        );
        
        $this->createPDF($html, $udala, $fitxa);
        $filename = $fitxa->getEspedientekodea() . "." . $fitxa->getDeskribapenaeu();
        return new BinaryFileResponse($this->pdfPath.'/'.$filename.'.pdf');
    }

    private function createPDF($html, $udala, Fitxa $fitxa): void {
        $pdf = $this->tcpdfController->create(
            'vertical',
            PDF_UNIT,
            PDF_PAGE_FORMAT,
            true,
            'UTF-8',
            false
        );        
        $pdf->SetAuthor( $udala );
        $pdf->SetTitle( ( $fitxa->getDeskribapenaeu() ) );
        $pdf->SetSubject( $fitxa->getDeskribapenaes() );
        $pdf->setFontSubsetting( true );
        $pdf->SetFont( 'helvetica', '', 11, '', true );
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
        $pdf->Output( $this->pdfPath.'/'.$filename . ".pdf", 'F' ); // This will output the PDF as a response directly
    }
}
