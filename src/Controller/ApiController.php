<?php
/**
 * User: iibarguren
 * Date: 31/05/16
 * Time: 10:09
 */

namespace App\Controller;

use App\Entity\Familia;
use App\Form\AtalaType;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\Routing\Annotation\Route;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Fitxa;
use App\Repository\EremuakRepository;
use App\Repository\EremuaRepository;
use App\Repository\FamiliaRepository;
use App\Repository\FitxaRepository;
use App\Repository\KanalmotaRepository;
use App\Repository\SailaRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Validator\Constraints\Regex;

/**
 * @Route("/api")
 */
class ApiController extends FOSRestController
{

//    private $em;
    private $sailaRepo;
    private $fitxaRepo;
    private $familiaRepo;
    private $eremuakRepo;
    private $kanalmotaRepo;

    public function __construct(
        EntityManagerInterface $em,
        SailaRepository $sailaRepo,
        FitxaRepository $fitxaRepo,
        FamiliaRepository $familiaRepo,
        EremuakRepository $eremuakRepo,
        KanalmotaRepository $kanalmotaRepo
    )
    {
//        $this->em = $em;
        $this->sailaRepo = $sailaRepo;
        $this->fitxaRepo = $fitxaRepo;
        $this->familiaRepo = $familiaRepo;
        $this->eremuakRepo = $eremuakRepo;
        $this->kanalmotaRepo = $kanalmotaRepo;
    }

    /****************************************************************************************************************
     ****************************************************************************************************************
     **** API SAC ***************************************************************************************************
     ****************************************************************************************************************
     ****************************************************************************************************************/

    /**
     * Udal baten Sail zerrenda
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Udal baten sail zerrenda",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     *
     * @param $udala
     *
     * @return array|View
     * @Annotations\View()
     *
     * @Get("/sailak/{udala}")
     */
    public function getSailakAction( Request $request, $udala )
    {
        $_format = $request->get('_format','json');
        $sailak = $this->sailaRepo->findByUdala($udala);
        if ( $sailak === null ) {
            return new View( 'there are no users exist', Response::HTTP_NOT_FOUND );
        }
        return $this->returnResponseDataAsFormat($sailak,$_format);
    }


    /**
     * Udal baten Azpisail baten fitxa zerrenda
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Udal baten Azpisail baten fitxa zerrenda",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     * @param $azpisailaid
     *
     * @return array|View
     * @Annotations\View(serializerGroups={"kontakud"})
     *
     * @Get("/azpisailenfitxak/{azpisailaid}")
     */
    public function getAzpisailenfitxakAction( Request $request, $azpisailaid)
    {
        $_format = $request->get('_format','json');
        $fitxak = $this->fitxaRepo->findByAzpisaila($azpisailaid);
        if ( $fitxak === null ) {
            return new View( 'there are no users exist', Response::HTTP_NOT_FOUND );
        }
        return $this->returnResponseDataAsFormat($fitxak,$_format);
    }


    /**
     * Udal baten Familia/Azpifamilia zerrenda
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Udal baten Familia/Azpifamilia zerrenda",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     *
     * @param $udala
     *
     * @return array|View
     * @Annotations\View()
     *
     * @Get("/familisarea/{udala}")
     */
    public function getFamilisareaAction( Request $request, $udala )
    {
        return $this->getSailakAction($request, $udala);
    }



    /****************************************************************************************************************
     ****************************************************************************************************************
     **** FIN API SAC ***********************************************************************************************
     ****************************************************************************************************************
     ****************************************************************************************************************/


    /**
     * Familia guztien zerrenda.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Bloke guztiak eskuratu",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     *
     * @return array data
     *
     * @Annotations\View()
     * @Get("/familiak/{udala}")
     */
    public function getFamiliakAction( Request $request, $udala )
    {
        $_format = $request->get('_format','json');
        $familiak = $this->familiaRepo->findByUdala($udala);
        if ( $familiak === null ) {
            return new View( 'there are no familiak', Response::HTTP_NOT_FOUND );
        }
        return $this->returnResponseDataAsFormat($familiak,$_format);
    }// "get_familiak"            [GET] /familiak/{udala}

    /**
     * Familia baten azpifamiliak zerrenda.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Familia baten azpifamiliak zerrenda.",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     *
     * @return array data
     *
     * @Annotations\View()
     * @Get("/azpifamiliak/{id}")
     */
    public function getAzpifamiliakAction( Request $request, $id )
    {
        $_format = $request->get('_format','json');
        $azpifamiliak = $this->familiaRepo->findBy([ 'parent' => $id ]);
        if ( $azpifamiliak === null ) {
            return new View( 'there are no azpifamiliak', Response::HTTP_NOT_FOUND );
        }
        return $this->returnResponseDataAsFormat($azpifamiliak,$_format);
    }// "get_azpifamiliak"            [GET] /azpifamiliak/{id}

    /**
     * Familia baten fitxa guztien zerrenda.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Familia baten fitxak eskuratu",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     *
     * @param $id
     *
     * @return array data
     *
     * @Annotations\View()
     * @Get("/fitxakbyfamilia/{id}")
     */
    public function getFitxakByFamiliaAction( Request $request, $id )
    {
        $_format = $request->get('_format','json');
        $fitxak = $this->fitxaRepo->findByFamilia($id);
        if ( $fitxak === null ) {
            return new View( 'there are no fitxak', Response::HTTP_NOT_FOUND );
        }
        return $this->returnResponseDataAsFormat($fitxak,$_format);
    }// "get_fitxak_by_familia"            [GET] /fitxakbyfamilia/{id}

    /**
     * Fitxa irakurri.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Fitxa irakurri",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     *
     * @return array data
     *
     * @Annotations\View()
     * @Get("/fitxa/{id}")
     */
    public function getFitxaAction( Fitxa $fitxa )
    {
        $eremuak = $this->eremuakRepo->findOneByUdala($fitxa->getUdala());
        $labelak = $this->eremuakRepo->findLabelakByUdala($fitxa->getUdala());
        $kanalmotak = $this->kanalmotaRepo->findAll();

        $response = new Response();
        $response->headers->set( 'Content-Type', 'application/xml; charset=utf-8' );

        return $this->render(
            'fitxapi.xml.twig',
            array(
                'fitxa'      => $fitxa,
                'eremuak'    => $eremuak,
                'labelak'    => $labelak,
                'kanalmotak' => $kanalmotak,
            ),
            $response
        );
    }// "get_fitxa"            [GET] /fitxa/{id}

    /**
     * Familia baten ordena sugeritu.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Familia baten ordena sugeritu.",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     *
     * @return array data
     *
     * @Annotations\View()
     * @Get("/familiaorden/{id}")
     */
    public function getFamiliaordenaAction( Request $request, $id )
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