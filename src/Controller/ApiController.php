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

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Fitxa;
use App\Repository\EremuakRepository;
use App\Repository\FamiliaRepository;
use App\Repository\FitxaRepository;
use App\Repository\KanalmotaRepository;
use App\Repository\SailaRepository;
use App\Repository\UdalaRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * API.
 */
#[Route(path: '/api')]
class ApiController extends AbstractFOSRestController
{

    public function __construct(
        private SailaRepository $sailaRepo,
        private FitxaRepository $fitxaRepo,
        private FamiliaRepository $familiaRepo,
        private EremuakRepository $eremuakRepo,
        private KanalmotaRepository $kanalmotaRepo,
        private UdalaRepository $udalaRepo
    )
    {
    }

    /****************************************************************************************************************
     ****************************************************************************************************************
     **** API SAC ***************************************************************************************************
     ****************************************************************************************************************
     ****************************************************************************************************************/

    /**
     * Udal baten Sail zerrenda
     *
    */
    #[OA\Response(
        response:200,
        description:"Udal baten Sail zerrenda",
        content: new OA\JsonContent(
            type:"array",
            items: new OA\Items(ref: new Model(type: Saila::class))
        )
    )]
    #[OA\Response(response: 404, description: "Udala ez da aurkitu")]
    #[Annotations\View()]
    #[Get(path: '/sailak/{udala}')]
    public function getSailak( Request $request, $udala )
    {
        $_format = $request->get('_format','json');
        $sailak = $this->sailaRepo->findByUdala($udala);
        if ( $sailak === null ) {
            return new View( 'Udala ez da aurkitu', Response::HTTP_NOT_FOUND );
        }
        return $this->returnResponseDataAsFormat($sailak,$_format);
    }


    /**
     * Udal baten Azpisail baten fitxa zerrenda
     *
     */
    #[OA\Response(
        response:200,
        description:"Udal baten Azpisail baten fitxa zerrenda",
        content: new OA\JsonContent(
            type:"array",
            items: new OA\Items(ref: new Model(type: Fitxa::class))
        )
    )]
    #[OA\Response(response: 404, description: "azpisaila ez da existitzen")]
    #[Annotations\View(serializerGroups: ["kontakud"])]
    #[Get(path: '/azpisailenfitxak/{azpisailaid}')]
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
     */
    #[OA\Response(
        response:200,
        description:"Udal baten Familia/Azpifamilia zerrenda",
        content: new OA\JsonContent(
            type:"array",
            items: new OA\Items(ref: new Model(type: Fitxa::class))
        )
    )]
    #[OA\Response(response: 404, description: "Udala ez da aurkitu")]
    #[Annotations\View()]
    #[Get(path: '/familisarea/{udala}')]
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
     */
    #[OA\Response(
        response:200,
        description:"Familia guztien zerrenda",
        content: new OA\JsonContent(
            type:"array",
            items: new OA\Items(ref: new Model(type: Familia::class))
        )
    )]
    #[OA\Response(response: 404, description: "Udala ez da aurkitu")]
    #[Annotations\View()]
    #[Get(path: '/familiak/{udalKodea}')]
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
     */
    #[OA\Response(
        response:200,
        description:"Familia baten azpifamiliak zerrenda familia gurasoaren identifikatzailea adierazita",
        content: new OA\JsonContent(
            type:"array",
            items: new OA\Items(ref: new Model(type: Familia::class))
        )
    )]
    #[OA\Response(response: 404, description: "Familia hori ez da existitzen")]
    #[Annotations\View()]
    #[Get(path: '/azpifamiliak/{id}', options: [ 'expose' => true])]
    public function getAzpifamiliak( Request $request, $id )
    {
        $_format = $request->get('_format','json');
        $familia = $this->familiaRepo->find($id);
        if ($familia === null) {
            return new View( 'Familia hori ez da existitzen', Response::HTTP_NOT_FOUND );
        }
        $azpifamiliak = $this->familiaRepo->findBy([ 'parent' => $id ]);
        return $this->returnResponseDataAsFormat($azpifamiliak,$_format);
    }

    /**
     * Familia baten fitxa guztien zerrenda.
     *
     */
    #[OA\Response(
        response:200,
        description:"Familia baten fitxa guztien zerrenda",
        content: new OA\JsonContent(
            type:"array",
            items: new OA\Items(ref: new Model(type: Fitxa::class))
        )
    )]
    #[OA\Response(response: 404, description: "Ez dago fitxarik")]
    #[Annotations\View()]
    #[Get(path: '/fitxakbyfamilia/{id}')]
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
     */
    #[OA\Response(
        response:200,
        description:"Fitxa irakurri XML formatuan fitxa identifikatzailea adierazita",
        content: new OA\JsonContent(
            type:"array",
            items: new OA\Items(ref: new Model(type: Fitxa::class))
        )
    )]
    #[OA\Response(response: 404, description: "Ez dago fitxarik")]
    #[Annotations\View()]
    #[Get(path: '/fitxa/{id}')]
    public function getFitxa( int $id ): Response
    {
        $fitxa = $this->fitxaRepo->find($id); 
        if ( null === $fitxa ) {
            return new JsonResponse('Ez dago fitxarik', Response::HTTP_NOT_FOUND );
        }
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
     */
    #[OA\Response(
        response:200,
        description:"Familia baten ordena sugeritu",
        content: new OA\JsonContent(
            type:"array",
            items: new OA\Items(ref: new Model(type: Fitxa::class))
        )
    )]
    #[OA\Response(response: 404, description: "Familia ez da existitzen")]
    #[Annotations\View()]
    #[Get(path: '/familiaorden/{id}')]
    public function getFamiliaordena( Request $request, $id )
    {
        $_format = $request->get('_format','json');
        $familia = $this->familiaRepo->find($id);

        if ( $familia === null ) {
            return new View( "Familia ez da existitzen: $id", Response::HTTP_NOT_FOUND );
        }
        $ordena = (int)$familia->getOrdena();
        $ordena += 1;

        return $this->returnResponseDataAsFormat($ordena,$_format);
    }// "get_familiaorden"            [GET] /familiaorden/{id}

    private function returnResponseDataAsFormat($data, $_format = 'json', $template = null, $templateData = []) {
        $view = View::create();
        $view->setData($data);

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