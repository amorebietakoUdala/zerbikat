<?php
/**
 * User: iibarguren
 * Date: 31/05/16
 * Time: 10:09
 */

namespace App\Controller;

use App\Entity\Familia;
use App\Form\AtalaType;
use App\Entity\Saila;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Fitxa;
use App\Repository\EremuakRepository;
use App\Repository\FamiliaRepository;
use App\Repository\FitxaRepository;
use App\Repository\KanalmotaRepository;
use App\Repository\SailaRepository;
use App\Repository\UdalaRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * API.
 *
 * @Route("/api")
 */
class ApiController extends AbstractFOSRestController
{

//    private $em;
    private $sailaRepo;
    private $fitxaRepo;
    private $familiaRepo;
    private $eremuakRepo;
    private $kanalmotaRepo;
    private $udalaRepo;

    public function __construct(
        SailaRepository $sailaRepo,
        FitxaRepository $fitxaRepo,
        FamiliaRepository $familiaRepo,
        EremuakRepository $eremuakRepo,
        KanalmotaRepository $kanalmotaRepo,
        UdalaRepository $udalaRepo
    )
    {
        $this->sailaRepo = $sailaRepo;
        $this->fitxaRepo = $fitxaRepo;
        $this->familiaRepo = $familiaRepo;
        $this->eremuakRepo = $eremuakRepo;
        $this->kanalmotaRepo = $kanalmotaRepo;
        $this->udalaRepo = $udalaRepo;
    }

    /****************************************************************************************************************
     ****************************************************************************************************************
     **** API SAC ***************************************************************************************************
     ****************************************************************************************************************
     ****************************************************************************************************************/

    /**
     * Udal baten Sail zerrenda
     *
     * @OA\Response(
     *     response=200,
     *     description="Udal baten Sail zerrenda",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Saila::class))
     *     )
     * )
     *
     * @param $udala
     *
     * @return array|View
     * @Annotations\View()
     *
     * @Get("/sailak/{udala}")
     */
    public function getSailak( Request $request, $udala )
    {
        $_format = $request->get('_format','json');
        $sailak = $this->sailaRepo->findByUdala($udala);
        if ( $sailak === null ) {
            return new View( 'udala ez da existitzen', Response::HTTP_NOT_FOUND );
        }
        return $this->returnResponseDataAsFormat($sailak,$_format);
    }


    /**
     * Udal baten Azpisail baten fitxa zerrenda
     *
     * @OA\Response(
     *     response=200,
     *     description="Udal baten Azpisail baten fitxa zerrenda",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Fitxa::class))
     *     )
     * )
     * @param $azpisailaid
     *
     * @return array|View
     * @Annotations\View(serializerGroups={"kontakud"})
     *
     * @Get("/azpisailenfitxak/{azpisailaid}")
     */
    public function getAzpisailenfitxak( Request $request, $azpisailaid)
    {
        $_format = $request->get('_format','json');
        $fitxak = $this->fitxaRepo->findByAzpisaila($azpisailaid);
        if ( $fitxak === null ) {
            return new View( 'azpisaila ez da existitzen', Response::HTTP_NOT_FOUND );
        }
        return $this->returnResponseDataAsFormat($fitxak,$_format);
    }


    /**
     * Udal baten Familia/Azpifamilia zerrenda
     *
     * @OA\Response(
     *     response=200,
     *     description="Udal baten Familia/Azpifamilia zerrenda",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Fitxa::class))
     *     )
     * )
     * 
     * @param $udala
     *
     * @return array|View
     * @Annotations\View()
     *
     * @Get("/familisarea/{udala}")
     */
    public function getFamilisarea( Request $request, $udala )
    {
        return $this->getSailak($request, $udala);
    }

    /****************************************************************************************************************
     ****************************************************************************************************************
     **** FIN API SAC ***********************************************************************************************
     ****************************************************************************************************************
     ****************************************************************************************************************/


    /**
     * Familia guztien zerrenda
     *
     * @OA\Response(
     *     response=200,
     *     description="Familia guztien zerrenda",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Familia::class))
     *     )
     * )
     * @OA\Response(
     *     response=404,
     *     description="Udala ez da aurkitu",
     * )
     *
     * @param $udalKodea
     * 
     * @return array|View
     * @Annotations\View()
     * @Get("/familiak/{udalKodea}")
     */
    public function getFamiliak( Request $request, $udalKodea )
    {
        $_format = $request->get('_format','json');
        $udala = $this->udalaRepo->findBy(['kodea' => $udalKodea]);
        if ( null === $udala ) {
            return new View( 'udala ez da existitzen', Response::HTTP_NOT_FOUND );
        }
        $familiak = $this->familiaRepo->findByUdala($udalKodea);
        if ( $familiak === null ) {
            return new View( 'Ez dago familiarik', Response::HTTP_NOT_FOUND );
        }
        return $this->returnResponseDataAsFormat($familiak,$_format);
    }

    /**
     * Familia baten azpifamiliak zerrenda familia gurasoaren identifikatzailea adierazita
     *
     * @return array data
     *
     * @Annotations\View()
     * @Get("/azpifamiliak/{id}", options={"expose"=true})
     */
    public function getAzpifamiliak( Request $request, $id )
    {
        $_format = $request->get('_format','json');
        $azpifamiliak = $this->familiaRepo->findBy([ 'parent' => $id ]);
        if ( $azpifamiliak === null ) {
            return new View( 'Ez dago azpifamiliarik', Response::HTTP_NOT_FOUND );
        }
        return $this->returnResponseDataAsFormat($azpifamiliak,$_format);
    }

    /**
     * Familia baten fitxa guztien zerrenda.
     *
     * @OA\Response(
     *     response=200,
     *     description="Familia baten fitxa guztien zerrenda",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Fitxa::class))
     *     )
     * )
     *
     * @param $id
     *
     * @return array data
     *
     * @Annotations\View()
     * @Get("/fitxakbyfamilia/{id}")
     */
    public function getFitxakByFamilia( Request $request, $id )
    {
        $_format = $request->get('_format','json');
        $fitxak = $this->fitxaRepo->findByFamilia($id);
        if ( $fitxak === null ) {
            return new View( 'Ez dago fitxarik', Response::HTTP_NOT_FOUND );
        }
        return $this->returnResponseDataAsFormat($fitxak,$_format);
    }

    /**
     * Fitxa irakurri XML formatuan fitxa identifikatzailea adierazita
     *
     * @OA\Response(
     *     response=200,
     *     description="Familia baten ordena sugeritu"
     * )
     * 
     * @return Fitxa|null
     *
     * @Annotations\View()
     * @Get("/fitxa/{id}")
     */
    public function getFitxa( Fitxa $fitxa ): Response
    {
        $eremuak = $this->eremuakRepo->findOneByUdala($fitxa->getUdala());
        $labelak = $this->eremuakRepo->findLabelakByUdala($fitxa->getUdala());
        $kanalmotak = $this->kanalmotaRepo->findAll();

        $response = new Response();
        $response->headers->set( 'Content-Type', 'application/xml; charset=utf-8' );

        return $this->render('fitxapi.xml.twig',[
                'fitxa'      => $fitxa, 
                'eremuak'    => $eremuak, 
                'labelak'    => $labelak, 
                'kanalmotak' => $kanalmotak
            ],
            $response
        );
    }

    /**
     * Familia baten ordena sugeritu.
     *
     * @OA\Response(
     *     response=200,
     *     description="Familia baten ordena sugeritu"
     * )
     *
     * @return array data
     *
     * @Annotations\View()
     * @Get("/familiaorden/{id}")
     */
    public function getFamiliaordena( Request $request, $id )
    {
        $_format = $request->get('_format','json');
        $familia = $this->familiaRepo->find($id);
        // TODO Cambiar la respuesta cuando es null porque ésta no funciona bien. Pide una plantilla twig que no está desarrollada
        if ( $familia === null ) {
            return new View( "there is no familia with id $id", Response::HTTP_NOT_FOUND );
        }
        $ordena = (int)$familia->getOrdena();
        $ordena += 1;

        return $this->returnResponseDataAsFormat($ordena,$_format);
    }// "get_familiaorden"            [GET] /familiaorden/{id}

    private function returnResponseDataAsFormat($data, $_format = 'json', $template = null, $templateData = []) {
        $view = View::create();
        $view->setData($data);
        //dump($_format);die;
        if (null !== $_format && $_format === 'html' ) {
            if ($template !== null) {
                $view->setTemplate($template);
                $view->setTemplateData($templateData);
                $view->setFormat('html');
            }
        }
        if (null !== $_format && $_format === 'json') {
            $view->setFormat('json'); 
            $view->setHeaders([
                'content-type' => 'application/json; charset=utf-8',
                'access-control-allow-origin' => '*',
            ]);
        }
        return $view;
    }

}