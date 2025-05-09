<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Attribute\UdalaEgiaztatu;
use App\Repository\EremuakRepository;

#[UdalaEgiaztatu(userFieldName: "udala_id")]
#[ORM\Table(name: 'eremuak')]
#[ORM\Entity(repositoryClass: EremuakRepository::class)]
class Eremuak
{
    /**
     * @var integer
     */
    #[ORM\Column(name: 'id', type: 'bigint')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;


    /**
     * @var oharraktext
     */
    #[ORM\Column(name: 'oharraktext', type: 'boolean', nullable: false, options: ['default' => true])]
    private $oharraktext = true;

    /**
     * @var oharraklabeleu
     */
    #[ORM\Column(name: 'oharraklabeleu', type: 'string', length: 255, nullable: true, options: ['default' => 'OHARRAK'])]
    private $oharraklabeleu = "OHARRAK";

    /**
     * @var oharraklabeles
     */
    #[ORM\Column(name: 'oharraklabeles', type: 'string', length: 255, nullable: true, options: ['default' => 'OBSERVACIONES'])]
    private $oharraklabeles = "OBSERVACIONES";


    /**
     * @var helburuatext
     */
    #[ORM\Column(name: 'helburuatext', type: 'boolean', nullable: true, options: ['default' => false])]
    private $helburuatext = false;

    /**
     * @var helburualabeleu
     */
    #[ORM\Column(name: 'helburualabeleu', type: 'string', length: 255, nullable: true)]
    private $helburualabeleu = "ZER DA? ZERTARAKO DA?";

    /**
     * @var helburualabeles
     */
    #[ORM\Column(name: 'helburualabeles', type: 'string', length: 255, nullable: true)]
    private $helburualabeles = "¿QUÉ ES? ¿PARA QUÉ?";


    /**
     * @var ebazpensinpli
     */
    #[ORM\Column(name: 'ebazpensinpli', type: 'boolean', nullable: true, options: ['default' => false])]
    private $ebazpensinpli = false;

    /**
     * @var ebazpensinplilabeleu
     */
    #[ORM\Column(name: 'ebazpensinplilabeleu', type: 'string', length: 255, nullable: true)]
    private $ebazpensinplilabeleu = "Prozedura sinplifikatua onartzen da? (30 egun)";

    /**
     * @var ebazpensinplilabeles
     */
    #[ORM\Column(name: 'ebazpensinplilabeles', type: 'string', length: 255, nullable: true)]
    private $ebazpensinplilabeles = "Admite procedimiento simplificado? ";


    /**
     * @var arduraaitorpena
     */
    #[ORM\Column(name: 'arduraaitorpena', type: 'boolean', nullable: true, options: ['default' => false])]
    private $arduraaitorpena = false;

    /**
     * @var arduraaitorpenalabeleu
     */
    #[ORM\Column(name: 'arduraaitorpenalabeleu', type: 'string', length: 255, nullable: true)]
    private $arduraaitorpenalabeleu = "Erantzunkizun-aitorpena onartzen da? ";

    /**
     * @var arduraaitorpenalabeles
     */
    #[ORM\Column(name: 'arduraaitorpenalabeles', type: 'string', length: 255, nullable: true)]
    private $arduraaitorpenalabeles = "Admite declaración de responsabilidad?";



    /**
     * @var aurreikusi
     */
    #[ORM\Column(name: 'aurreikusi', type: 'boolean', nullable: true, options: ['default' => false])]
    private $aurreikusi = false;

    /**
     * @var aurreikusilabeleu
     */
    #[ORM\Column(name: 'aurreikusilabeleu', type: 'string', length: 255, nullable: true)]
    private $aurreikusilabeleu = "Aurreikusitako epea";

    /**
     * @var aurreikusilabeles
     */
    #[ORM\Column(name: 'aurreikusilabeles', type: 'string', length: 255, nullable: true)]
    private $aurreikusilabeles = "Plazo estimado";

    /**
     * @var arrunta
     */
    #[ORM\Column(name: 'arrunta', type: 'boolean', nullable: true, options: ['default' => false])]
    private $arrunta = false;

    /**
     * @var arruntalabeleu
     */
    #[ORM\Column(name: 'arruntalabeleu', type: 'string', length: 255, nullable: true)]
    private $arruntalabeleu = "Prozedura arrunta. Legezko gehienezko epea";

    /**
     * @var arruntalabeles
     */
    #[ORM\Column(name: 'arruntalabeles', type: 'string', length: 255, nullable: true)]
    private $arruntalabeles = "Procedimiento habitual. Plazo limite legal";


    /**
     * @var isiltasunadmin
     */
    #[ORM\Column(name: 'isiltasunadmin', type: 'boolean', nullable: true, options: ['default' => false])]
    private $isiltasunadmin = false;

    /**
     * @var isiltasunadminlabeleu
     */
    #[ORM\Column(name: 'isiltasunadminlabeleu', type: 'string', length: 255, nullable: true)]
    private $isiltasunadminlabeleu = "Isiltasun-administratiboaren izaera";

    /**
     * @var isiltasunadminlabeles
     */
    #[ORM\Column(name: 'isiltasunadminlabeles', type: 'string', length: 255, nullable: true)]
    private $isiltasunadminlabeles = "Carácter del silencio administrativo";




    /**
     * @var norkeskatutext
     */
    #[ORM\Column(name: 'norkeskatutext', type: 'boolean', nullable: true, options: ['default' => false])]
    private $norkeskatutext = false;

    /**
     * @var norkeskatutable
     */
    #[ORM\Column(name: 'norkeskatutable', type: 'boolean', nullable: true, options: ['default' => false])]
    private $norkeskatutable = false;


    /**
     * @var norkeskatulabeleu
     */
    #[ORM\Column(name: 'norkeskatulabeleu', type: 'string', length: 255, nullable: true)]
    private $norkeskatulabeleu = "NORK ESKA DEZAKE?";

    /**
     * @var norkeskatulabeles
     */
    #[ORM\Column(name: 'norkeskatulabeles', type: 'string', length: 255, nullable: true)]
    private $norkeskatulabeles = "¿QUIÉN LO PUEDE SOLICITAR?";



    /**
     * @var dokumentazioatext
     */
    #[ORM\Column(name: 'dokumentazioatext', type: 'boolean', nullable: true, options: ['default' => false])]
    private $dokumentazioatext = false;

    /**
     * @var dokumentazioatable
     */
    #[ORM\Column(name: 'dokumentazioatable', type: 'boolean', nullable: true)]
    private $dokumentazioatable = false;


    /**
     * @var dokumentazioalabeleu
     */
    #[ORM\Column(name: 'dokumentazioalabeleu', type: 'string', length: 255, nullable: true)]
    private $dokumentazioalabeleu = "AURKEZTU BEHARREKO AGIRIAK";

    /**
     * @var dokumentazioalabeles
     */
    #[ORM\Column(name: 'dokumentazioalabeles', type: 'string', length: 255, nullable: true)]
    private $dokumentazioalabeles = "DOCUMENTACIÓN A APORTAR";



    /**
     * @var kostuatext
     */
    #[ORM\Column(name: 'kostuatext', type: 'boolean', nullable: true, options: ['default' => false])]
    private $kostuatext = false;

    /**
     * @var kostuatable
     */
    #[ORM\Column(name: 'kostuatable', type: 'boolean', nullable: true, options: ['default' => false])]
    private $kostuatable = false;


    /**
     * @var kostualabeleu
     */
    #[ORM\Column(name: 'kostualabeleu', type: 'string', length: 255, nullable: true)]
    private $kostualabeleu = "ZENBAT KOSTATZEN DA?";

    /**
     * @var kostualabeles
     */
    #[ORM\Column(name: 'kostualabeles', type: 'string', length: 255, nullable: true)]
    private $kostualabeles = "¿CUÁNTO CUESTA?";



    /**
     * @var araudiatext
     */
    #[ORM\Column(name: 'araudiatext', type: 'boolean', nullable: true, options: ['default' => true])]
    private $araudiatext = true;

    /**
     * @var araudiatable
     */
    #[ORM\Column(name: 'araudiatable', type: 'boolean', nullable: true, options: ['default' => true])]
    private $araudiatable = true;


    /**
     * @var araudialabeleu
     */
    #[ORM\Column(name: 'araudialabeleu', type: 'string', length: 255, nullable: true)]
    private $araudialabeleu = "ARAUDI APLIKAGARRIA";

    /**
     * @var araudialabeles
     */
    #[ORM\Column(name: 'araudialabeles', type: 'string', length: 255, nullable: true)]
    private $araudialabeles = "NORMATIVA APLICABLE";


    /**
     * @var prozeduratext
     */
    #[ORM\Column(name: 'prozeduratext', type: 'boolean', nullable: true, options: ['default' => false])]
    private $prozeduratext = false;

    /**
     * @var prozeduratable
     */
    #[ORM\Column(name: 'prozeduratable', type: 'boolean', nullable: true, options: ['default' => false])]
    private $prozeduratable = false;


    /**
     * @var prozeduralabeleu
     */
    #[ORM\Column(name: 'prozeduralabeleu', type: 'string', length: 255, nullable: true)]
    private $prozeduralabeleu = "ESKAERA JASO ONDOREN JARRAITU BEHARREKO PROZEDURA";

    /**
     * @var prozeduralabeles
     */
    #[ORM\Column(name: 'prozeduralabeles', type: 'string', length: 255, nullable: true)]
    private $prozeduralabeles = "PROCEDIMIENTO A SEGUIR DESPUÉS DE LA SOLICITUD";




    /**
     * @var doklaguntext
     */
    #[ORM\Column(name: 'doklaguntext', type: 'boolean', nullable: true, options: ['default' => false])]
    private $doklaguntext = false;

    /**
     * @var doklaguntable
     */
    #[ORM\Column(name: 'doklaguntable', type: 'boolean', nullable: true, options: ['default' => false])]
    private $doklaguntable = false;


    /**
     * @var doklagunlabeleu
     */
    #[ORM\Column(name: 'doklagunlabeleu', type: 'string', length: 255, nullable: true)]
    private $doklagunlabeleu = "DOKUMENTAZIO LAGUNGARRIA";

    /**
     * @var doklagunlabeles
     */
    #[ORM\Column(name: 'doklagunlabeles', type: 'string', length: 255, nullable: true)]
    private $doklagunlabeles = "DOCUMENTACIÓN AUXILIAR";


    /**
     * @var datuenbabesatext
     */
    #[ORM\Column(name: 'datuenbabesatext', type: 'boolean', nullable: true, options: ['default' => false])]
    private $datuenbabesatext = false;

    /**
     * @var datuenbabesatable
     */
    #[ORM\Column(name: 'datuenbabesatable', type: 'boolean', nullable: true, options: ['default' => false])]
    private $datuenbabesatable = false;


    /**
     * @var datuenbabesalabeleu
     */
    #[ORM\Column(name: 'datuenbabesalabeleu', type: 'string', length: 255, nullable: true)]
    private $datuenbabesalabeleu = "DATU PERTSONALEN BABESA";

    /**
     * @var datuenbabesalabeles
     */
    #[ORM\Column(name: 'datuenbabesalabeles', type: 'string', length: 255, nullable: true)]
    private $datuenbabesalabeles = "PROTECCIÓN DE DATOS DE CARÁCTER PERSONAL";

    /**
     * @var azpisailatable
     */
    #[ORM\Column(name: 'azpisailatable', type: 'boolean', nullable: true, options: ['default' => false])]
    private $azpisailatable = false;

    /**
     * @var azpisailalabeleu
     */
    #[ORM\Column(name: 'azpisailalabeleu', type: 'string', length: 255, nullable: true)]
    private $azpisailalabeleu = "IZAPIDETZEKO ARDURA DUEN UDAL SAILA";

    /**
     * @var azpisailalabeles
     */
    #[ORM\Column(name: 'azpisailalabeles', type: 'string', length: 255, nullable: true)]
    private $azpisailalabeles = "DEPARTAMENTO MUNICIPAL RESPONSABLE DE LA TRAMITACIÓN";

    /**
     * @var norkebatzitext
     */
    #[ORM\Column(name: 'norkebatzitext', type: 'boolean', nullable: true, options: ['default' => false])]
    private $norkebatzitext = false;

    /**
     * @var norkebatzitable
     */
    #[ORM\Column(name: 'norkebatzitable', type: 'boolean', nullable: true, options: ['default' => false])]
    private $norkebatzitable = false;


    /**
     * @var norkebatzilabeleu
     */
    #[ORM\Column(name: 'norkebatzilabeleu', type: 'string', length: 255, nullable: true)]
    private $norkebatzilabeleu = "NORK ONARTU BEHAR DU?";

    /**
     * @var norkebatzilabeles
     */
    #[ORM\Column(name: 'norkebatzilabeles', type: 'string', length: 255, nullable: true)]
    private $norkebatzilabeles = "¿QUIÉN LO APRUEBA?";




    /**
     * @var besteak1text
     */
    #[ORM\Column(name: 'besteak1text', type: 'boolean', nullable: true, options: ['default' => false])]
    private $besteak1text = false;

    /**
     * @var besteak1table
     */
    #[ORM\Column(name: 'besteak1table', type: 'boolean', nullable: true, options: ['default' => false])]
    private $besteak1table = false;


    /**
     * @var besteak1labeleu
     */
    #[ORM\Column(name: 'besteak1labeleu', type: 'string', length: 255, nullable: true)]
    private $besteak1labeleu = "";

    /**
     * @var besteak1labeles
     */
    #[ORM\Column(name: 'besteak1labeles', type: 'string', length: 255, nullable: true)]
    private $besteak1labeles = "";

    /**
     * @var besteak2text
     */
    #[ORM\Column(name: 'besteak2text', type: 'boolean', nullable: true, options: ['default' => false])]
    private $besteak2text = false;

    /**
     * @var besteak2table
     */
    #[ORM\Column(name: 'besteak2table', type: 'boolean', nullable: true, options: ['default' => false])]
    private $besteak2table = false;


    /**
     * @var besteak2labeleu
     */
    #[ORM\Column(name: 'besteak2labeleu', type: 'string', length: 255, nullable: true)]
    private $besteak2labeleu = "";

    /**
     * @var besteak2labeles
     */
    #[ORM\Column(name: 'besteak2labeles', type: 'string', length: 255, nullable: true)]
    private $besteak2labeles = "";


    /**
     * @var besteak3text
     */
    #[ORM\Column(name: 'besteak3text', type: 'boolean', nullable: true, options: ['default' => false])]
    private $besteak3text = false;

    /**
     * @var besteak3table
     */
    #[ORM\Column(name: 'besteak3table', type: 'boolean', nullable: true, options: ['default' => false])]
    private $besteak3table = false;

    /**
     * @var besteak3labeleu
     */
    #[ORM\Column(name: 'besteak3labeleu', type: 'string', length: 255, nullable: true)]
    private $besteak3labeleu = "";

    /**
     * @var besteak3labeles
     */
    #[ORM\Column(name: 'besteak3labeles', type: 'string', length: 255, nullable: true)]
    private $besteak3labeles = "";

    /**
     * @var kanalatext
     */
    #[ORM\Column(name: 'kanalatext', type: 'boolean', nullable: true, options: ['default' => false])]
    private $kanalatext = false;

    /**
     * @var kanalatable
     */
    #[ORM\Column(name: 'kanalatable', type: 'boolean', nullable: true, options: ['default' => false])]
    private $kanalatable = false;

    /**
     * @var kanalalabeleu
     */
    #[ORM\Column(name: 'kanalalabeleu', type: 'string', length: 255, nullable: true)]
    private $kanalalabeleu = "NON ESKATZEN DA?";

    /**
     * @var kanalalabeles
     */
    #[ORM\Column(name: 'kanalalabeles', type: 'string', length: 255, nullable: true)]
    private $kanalalabeles = "¿DÓNDE SE SOLICITA?";


    /**
     * @var epealabeleu
     */
    #[ORM\Column(name: 'epealabeleu', type: 'string', length: 255, nullable: true)]
    private $epealabeleu = "PROZEDURAREN EPEA";

    /**
     * @var epealabeles
     */
    #[ORM\Column(name: 'epealabeles', type: 'string', length: 255, nullable: true)]
    private $epealabeles = "PLAZO DEL PROCEDIMIENTO";

    /**
     * @var doanlabeleu
     */
    #[ORM\Column(name: 'doanlabeleu', type: 'string', length: 255, nullable: true)]
    private $doanlabeleu = "Doan";

    /**
     * @var doanlabeles
     */
    #[ORM\Column(name: 'doanlabeles', type: 'string', length: 255, nullable: true)]
    private $doanlabeles = "Gratuito";


    /**
     *          ERLAZIOAK
     */
    /**
     * @var Udala $udala
     *
     */

    #[ORM\OneToOne(targetEntity: Udala::class, inversedBy: 'eremuak', fetch: 'EAGER', cascade: ['remove'])]
    private $udala;


    /**
     * Constructor
     */
    public function __construct()
    {
        // $this->tramitealabeles = "RESUMEN DE TRÁMITES POSTERIORES";
        // $this->tramitealabeleu = "GEROAGOKO IZAPIDEEN LABURPENA";
        // $this->tramiteatable = false;
        // $this->tramiteatext = true;
    }


    /**
     *
     *      FUNTZIOAK
     *
     */

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set oharraktext
     *
     * @param boolean $oharraktext
     *
     * @return Eremuak
     */
    public function setOharraktext($oharraktext)
    {
        $this->oharraktext = $oharraktext;

        return $this;
    }

    /**
     * Get oharraktext
     *
     * @return boolean
     */
    public function getOharraktext()
    {
        return $this->oharraktext;
    }

    /**
     * Set oharraklabeleu
     *
     * @param string $oharraklabeleu
     *
     * @return Eremuak
     */
    public function setOharraklabeleu($oharraklabeleu)
    {
        $this->oharraklabeleu = $oharraklabeleu;

        return $this;
    }

    /**
     * Get oharraklabeleu
     *
     * @return string
     */
    public function getOharraklabeleu()
    {
        return $this->oharraklabeleu;
    }

    /**
     * Set oharraklabeles
     *
     * @param string $oharraklabeles
     *
     * @return Eremuak
     */
    public function setOharraklabeles($oharraklabeles)
    {
        $this->oharraklabeles = $oharraklabeles;

        return $this;
    }

    /**
     * Get oharraklabeles
     *
     * @return string
     */
    public function getOharraklabeles()
    {
        return $this->oharraklabeles;
    }

    /**
     * Set helburuatext
     *
     * @param boolean $helburuatext
     *
     * @return Eremuak
     */
    public function setHelburuatext($helburuatext)
    {
        $this->helburuatext = $helburuatext;

        return $this;
    }

    /**
     * Get helburuatext
     *
     * @return boolean
     */
    public function getHelburuatext()
    {
        return $this->helburuatext;
    }

    /**
     * Set helburualabeleu
     *
     * @param string $helburualabeleu
     *
     * @return Eremuak
     */
    public function setHelburualabeleu($helburualabeleu)
    {
        $this->helburualabeleu = $helburualabeleu;

        return $this;
    }

    /**
     * Get helburualabeleu
     *
     * @return string
     */
    public function getHelburualabeleu()
    {
        return $this->helburualabeleu;
    }

    /**
     * Set helburualabeles
     *
     * @param string $helburualabeles
     *
     * @return Eremuak
     */
    public function setHelburualabeles($helburualabeles)
    {
        $this->helburualabeles = $helburualabeles;

        return $this;
    }

    /**
     * Get helburualabeles
     *
     * @return string
     */
    public function getHelburualabeles()
    {
        return $this->helburualabeles;
    }

    /**
     * Set ebazpensinpli
     *
     * @param boolean $ebazpensinpli
     *
     * @return Eremuak
     */
    public function setEbazpensinpli($ebazpensinpli)
    {
        $this->ebazpensinpli = $ebazpensinpli;

        return $this;
    }

    /**
     * Get ebazpensinpli
     *
     * @return boolean
     */
    public function getEbazpensinpli()
    {
        return $this->ebazpensinpli;
    }

    /**
     * Set ebazpensinplilabeleu
     *
     * @param string $ebazpensinplilabeleu
     *
     * @return Eremuak
     */
    public function setEbazpensinplilabeleu($ebazpensinplilabeleu)
    {
        $this->ebazpensinplilabeleu = $ebazpensinplilabeleu;

        return $this;
    }

    /**
     * Get ebazpensinplilabeleu
     *
     * @return string
     */
    public function getEbazpensinplilabeleu()
    {
        return $this->ebazpensinplilabeleu;
    }

    /**
     * Set ebazpensinplilabeles
     *
     * @param string $ebazpensinplilabeles
     *
     * @return Eremuak
     */
    public function setEbazpensinplilabeles($ebazpensinplilabeles)
    {
        $this->ebazpensinplilabeles = $ebazpensinplilabeles;

        return $this;
    }

    /**
     * Get ebazpensinplilabeles
     *
     * @return string
     */
    public function getEbazpensinplilabeles()
    {
        return $this->ebazpensinplilabeles;
    }

    /**
     * Set arduraaitorpena
     *
     * @param boolean $arduraaitorpena
     *
     * @return Eremuak
     */
    public function setArduraaitorpena($arduraaitorpena)
    {
        $this->arduraaitorpena = $arduraaitorpena;

        return $this;
    }

    /**
     * Get arduraaitorpena
     *
     * @return boolean
     */
    public function getArduraaitorpena()
    {
        return $this->arduraaitorpena;
    }

    /**
     * Set arduraaitorpenalabeleu
     *
     * @param string $arduraaitorpenalabeleu
     *
     * @return Eremuak
     */
    public function setArduraaitorpenalabeleu($arduraaitorpenalabeleu)
    {
        $this->arduraaitorpenalabeleu = $arduraaitorpenalabeleu;

        return $this;
    }

    /**
     * Get arduraaitorpenalabeleu
     *
     * @return string
     */
    public function getArduraaitorpenalabeleu()
    {
        return $this->arduraaitorpenalabeleu;
    }

    /**
     * Set arduraaitorpenalabeles
     *
     * @param string $arduraaitorpenalabeles
     *
     * @return Eremuak
     */
    public function setArduraaitorpenalabeles($arduraaitorpenalabeles)
    {
        $this->arduraaitorpenalabeles = $arduraaitorpenalabeles;

        return $this;
    }

    /**
     * Get arduraaitorpenalabeles
     *
     * @return string
     */
    public function getArduraaitorpenalabeles()
    {
        return $this->arduraaitorpenalabeles;
    }

    /**
     * Set aurreikusi
     *
     * @param boolean $aurreikusi
     *
     * @return Eremuak
     */
    public function setAurreikusi($aurreikusi)
    {
        $this->aurreikusi = $aurreikusi;

        return $this;
    }

    /**
     * Get aurreikusi
     *
     * @return boolean
     */
    public function getAurreikusi()
    {
        return $this->aurreikusi;
    }

    /**
     * Set aurreikusilabeleu
     *
     * @param string $aurreikusilabeleu
     *
     * @return Eremuak
     */
    public function setAurreikusilabeleu($aurreikusilabeleu)
    {
        $this->aurreikusilabeleu = $aurreikusilabeleu;

        return $this;
    }

    /**
     * Get aurreikusilabeleu
     *
     * @return string
     */
    public function getAurreikusilabeleu()
    {
        return $this->aurreikusilabeleu;
    }

    /**
     * Set aurreikusilabeles
     *
     * @param string $aurreikusilabeles
     *
     * @return Eremuak
     */
    public function setAurreikusilabeles($aurreikusilabeles)
    {
        $this->aurreikusilabeles = $aurreikusilabeles;

        return $this;
    }

    /**
     * Get aurreikusilabeles
     *
     * @return string
     */
    public function getAurreikusilabeles()
    {
        return $this->aurreikusilabeles;
    }

    /**
     * Set arrunta
     *
     * @param boolean $arrunta
     *
     * @return Eremuak
     */
    public function setArrunta($arrunta)
    {
        $this->arrunta = $arrunta;

        return $this;
    }

    /**
     * Get arrunta
     *
     * @return boolean
     */
    public function getArrunta()
    {
        return $this->arrunta;
    }

    /**
     * Set arruntalabeleu
     *
     * @param string $arruntalabeleu
     *
     * @return Eremuak
     */
    public function setArruntalabeleu($arruntalabeleu)
    {
        $this->arruntalabeleu = $arruntalabeleu;

        return $this;
    }

    /**
     * Get arruntalabeleu
     *
     * @return string
     */
    public function getArruntalabeleu()
    {
        return $this->arruntalabeleu;
    }

    /**
     * Set arruntalabeles
     *
     * @param string $arruntalabeles
     *
     * @return Eremuak
     */
    public function setArruntalabeles($arruntalabeles)
    {
        $this->arruntalabeles = $arruntalabeles;

        return $this;
    }

    /**
     * Get arruntalabeles
     *
     * @return string
     */
    public function getArruntalabeles()
    {
        return $this->arruntalabeles;
    }

    /**
     * Set isiltasunadmin
     *
     * @param boolean $isiltasunadmin
     *
     * @return Eremuak
     */
    public function setIsiltasunadmin($isiltasunadmin)
    {
        $this->isiltasunadmin = $isiltasunadmin;

        return $this;
    }

    /**
     * Get isiltasunadmin
     *
     * @return boolean
     */
    public function getIsiltasunadmin()
    {
        return $this->isiltasunadmin;
    }

    /**
     * Set isiltasunadminlabeleu
     *
     * @param string $isiltasunadminlabeleu
     *
     * @return Eremuak
     */
    public function setIsiltasunadminlabeleu($isiltasunadminlabeleu)
    {
        $this->isiltasunadminlabeleu = $isiltasunadminlabeleu;

        return $this;
    }

    /**
     * Get isiltasunadminlabeleu
     *
     * @return string
     */
    public function getIsiltasunadminlabeleu()
    {
        return $this->isiltasunadminlabeleu;
    }

    /**
     * Set isiltasunadminlabeles
     *
     * @param string $isiltasunadminlabeles
     *
     * @return Eremuak
     */
    public function setIsiltasunadminlabeles($isiltasunadminlabeles)
    {
        $this->isiltasunadminlabeles = $isiltasunadminlabeles;

        return $this;
    }

    /**
     * Get isiltasunadminlabeles
     *
     * @return string
     */
    public function getIsiltasunadminlabeles()
    {
        return $this->isiltasunadminlabeles;
    }

    /**
     * Set norkeskatutext
     *
     * @param boolean $norkeskatutext
     *
     * @return Eremuak
     */
    public function setNorkeskatutext($norkeskatutext)
    {
        $this->norkeskatutext = $norkeskatutext;

        return $this;
    }

    /**
     * Get norkeskatutext
     *
     * @return boolean
     */
    public function getNorkeskatutext()
    {
        return $this->norkeskatutext;
    }

    /**
     * Set norkeskatutable
     *
     * @param boolean $norkeskatutable
     *
     * @return Eremuak
     */
    public function setNorkeskatutable($norkeskatutable)
    {
        $this->norkeskatutable = $norkeskatutable;

        return $this;
    }

    /**
     * Get norkeskatutable
     *
     * @return boolean
     */
    public function getNorkeskatutable()
    {
        return $this->norkeskatutable;
    }

    /**
     * Set norkeskatulabeleu
     *
     * @param string $norkeskatulabeleu
     *
     * @return Eremuak
     */
    public function setNorkeskatulabeleu($norkeskatulabeleu)
    {
        $this->norkeskatulabeleu = $norkeskatulabeleu;

        return $this;
    }

    /**
     * Get norkeskatulabeleu
     *
     * @return string
     */
    public function getNorkeskatulabeleu()
    {
        return $this->norkeskatulabeleu;
    }

    /**
     * Set norkeskatulabeles
     *
     * @param string $norkeskatulabeles
     *
     * @return Eremuak
     */
    public function setNorkeskatulabeles($norkeskatulabeles)
    {
        $this->norkeskatulabeles = $norkeskatulabeles;

        return $this;
    }

    /**
     * Get norkeskatulabeles
     *
     * @return string
     */
    public function getNorkeskatulabeles()
    {
        return $this->norkeskatulabeles;
    }

    /**
     * Set dokumentazioatext
     *
     * @param boolean $dokumentazioatext
     *
     * @return Eremuak
     */
    public function setDokumentazioatext($dokumentazioatext)
    {
        $this->dokumentazioatext = $dokumentazioatext;

        return $this;
    }

    /**
     * Get dokumentazioatext
     *
     * @return boolean
     */
    public function getDokumentazioatext()
    {
        return $this->dokumentazioatext;
    }

    /**
     * Set dokumentazioatable
     *
     * @param boolean $dokumentazioatable
     *
     * @return Eremuak
     */
    public function setDokumentazioatable($dokumentazioatable)
    {
        $this->dokumentazioatable = $dokumentazioatable;

        return $this;
    }

    /**
     * Get dokumentazioatable
     *
     * @return boolean
     */
    public function getDokumentazioatable()
    {
        return $this->dokumentazioatable;
    }

    /**
     * Set dokumentazioalabeleu
     *
     * @param string $dokumentazioalabeleu
     *
     * @return Eremuak
     */
    public function setDokumentazioalabeleu($dokumentazioalabeleu)
    {
        $this->dokumentazioalabeleu = $dokumentazioalabeleu;

        return $this;
    }

    /**
     * Get dokumentazioalabeleu
     *
     * @return string
     */
    public function getDokumentazioalabeleu()
    {
        return $this->dokumentazioalabeleu;
    }

    /**
     * Set dokumentazioalabeles
     *
     * @param string $dokumentazioalabeles
     *
     * @return Eremuak
     */
    public function setDokumentazioalabeles($dokumentazioalabeles)
    {
        $this->dokumentazioalabeles = $dokumentazioalabeles;

        return $this;
    }

    /**
     * Get dokumentazioalabeles
     *
     * @return string
     */
    public function getDokumentazioalabeles()
    {
        return $this->dokumentazioalabeles;
    }

    /**
     * Set kostuatext
     *
     * @param boolean $kostuatext
     *
     * @return Eremuak
     */
    public function setKostuatext($kostuatext)
    {
        $this->kostuatext = $kostuatext;

        return $this;
    }

    /**
     * Get kostuatext
     *
     * @return boolean
     */
    public function getKostuatext()
    {
        return $this->kostuatext;
    }

    /**
     * Set kostuatable
     *
     * @param boolean $kostuatable
     *
     * @return Eremuak
     */
    public function setKostuatable($kostuatable)
    {
        $this->kostuatable = $kostuatable;

        return $this;
    }

    /**
     * Get kostuatable
     *
     * @return boolean
     */
    public function getKostuatable()
    {
        return $this->kostuatable;
    }

    /**
     * Set kostualabeleu
     *
     * @param string $kostualabeleu
     *
     * @return Eremuak
     */
    public function setKostualabeleu($kostualabeleu)
    {
        $this->kostualabeleu = $kostualabeleu;

        return $this;
    }

    /**
     * Get kostualabeleu
     *
     * @return string
     */
    public function getKostualabeleu()
    {
        return $this->kostualabeleu;
    }

    /**
     * Set kostualabeles
     *
     * @param string $kostualabeles
     *
     * @return Eremuak
     */
    public function setKostualabeles($kostualabeles)
    {
        $this->kostualabeles = $kostualabeles;

        return $this;
    }

    /**
     * Get kostualabeles
     *
     * @return string
     */
    public function getKostualabeles()
    {
        return $this->kostualabeles;
    }

    /**
     * Set araudiatext
     *
     * @param boolean $araudiatext
     *
     * @return Eremuak
     */
    public function setAraudiatext($araudiatext)
    {
        $this->araudiatext = $araudiatext;

        return $this;
    }

    /**
     * Get araudiatext
     *
     * @return boolean
     */
    public function getAraudiatext()
    {
        return $this->araudiatext;
    }

    /**
     * Set araudiatable
     *
     * @param boolean $araudiatable
     *
     * @return Eremuak
     */
    public function setAraudiatable($araudiatable)
    {
        $this->araudiatable = $araudiatable;

        return $this;
    }

    /**
     * Get araudiatable
     *
     * @return boolean
     */
    public function getAraudiatable()
    {
        return $this->araudiatable;
    }

    /**
     * Set araudialabeleu
     *
     * @param string $araudialabeleu
     *
     * @return Eremuak
     */
    public function setAraudialabeleu($araudialabeleu)
    {
        $this->araudialabeleu = $araudialabeleu;

        return $this;
    }

    /**
     * Get araudialabeleu
     *
     * @return string
     */
    public function getAraudialabeleu()
    {
        return $this->araudialabeleu;
    }

    /**
     * Set araudialabeles
     *
     * @param string $araudialabeles
     *
     * @return Eremuak
     */
    public function setAraudialabeles($araudialabeles)
    {
        $this->araudialabeles = $araudialabeles;

        return $this;
    }

    /**
     * Get araudialabeles
     *
     * @return string
     */
    public function getAraudialabeles()
    {
        return $this->araudialabeles;
    }

    /**
     * Set prozeduratext
     *
     * @param boolean $prozeduratext
     *
     * @return Eremuak
     */
    public function setProzeduratext($prozeduratext)
    {
        $this->prozeduratext = $prozeduratext;

        return $this;
    }

    /**
     * Get prozeduratext
     *
     * @return boolean
     */
    public function getProzeduratext()
    {
        return $this->prozeduratext;
    }

    /**
     * Set prozeduratable
     *
     * @param boolean $prozeduratable
     *
     * @return Eremuak
     */
    public function setProzeduratable($prozeduratable)
    {
        $this->prozeduratable = $prozeduratable;

        return $this;
    }

    /**
     * Get prozeduratable
     *
     * @return boolean
     */
    public function getProzeduratable()
    {
        return $this->prozeduratable;
    }

    /**
     * Set prozeduralabeleu
     *
     * @param string $prozeduralabeleu
     *
     * @return Eremuak
     */
    public function setProzeduralabeleu($prozeduralabeleu)
    {
        $this->prozeduralabeleu = $prozeduralabeleu;

        return $this;
    }

    /**
     * Get prozeduralabeleu
     *
     * @return string
     */
    public function getProzeduralabeleu()
    {
        return $this->prozeduralabeleu;
    }

    /**
     * Set prozeduralabeles
     *
     * @param string $prozeduralabeles
     *
     * @return Eremuak
     */
    public function setProzeduralabeles($prozeduralabeles)
    {
        $this->prozeduralabeles = $prozeduralabeles;

        return $this;
    }

    /**
     * Get prozeduralabeles
     *
     * @return string
     */
    public function getProzeduralabeles()
    {
        return $this->prozeduralabeles;
    }

    /**
     * Set udala
     *
     * @param Udala $udala
     *
     * @return Eremuak
     */
    public function setUdala(Udala $udala = null)
    {
        $this->udala = $udala;

        return $this;
    }

    /**
     * Get udala
     *
     * @return Udala
     */
    public function getUdala()
    {
        return $this->udala;
    }

    /**
     * Set doklaguntext
     *
     * @param boolean $doklaguntext
     *
     * @return Eremuak
     */
    public function setDoklaguntext($doklaguntext)
    {
        $this->doklaguntext = $doklaguntext;

        return $this;
    }

    /**
     * Get doklaguntext
     *
     * @return boolean
     */
    public function getDoklaguntext()
    {
        return $this->doklaguntext;
    }

    /**
     * Set doklaguntable
     *
     * @param boolean $doklaguntable
     *
     * @return Eremuak
     */
    public function setDoklaguntable($doklaguntable)
    {
        $this->doklaguntable = $doklaguntable;

        return $this;
    }

    /**
     * Get doklaguntable
     *
     * @return boolean
     */
    public function getDoklaguntable()
    {
        return $this->doklaguntable;
    }

    /**
     * Set doklagunlabeleu
     *
     * @param string $doklagunlabeleu
     *
     * @return Eremuak
     */
    public function setDoklagunlabeleu($doklagunlabeleu)
    {
        $this->doklagunlabeleu = $doklagunlabeleu;

        return $this;
    }

    /**
     * Get doklagunlabeleu
     *
     * @return string
     */
    public function getDoklagunlabeleu()
    {
        return $this->doklagunlabeleu;
    }

    /**
     * Set doklagunlabeles
     *
     * @param string $doklagunlabeles
     *
     * @return Eremuak
     */
    public function setDoklagunlabeles($doklagunlabeles)
    {
        $this->doklagunlabeles = $doklagunlabeles;

        return $this;
    }

    /**
     * Get doklagunlabeles
     *
     * @return string
     */
    public function getDoklagunlabeles()
    {
        return $this->doklagunlabeles;
    }

    /**
     * Set datuenbabesatext
     *
     * @param boolean $datuenbabesatext
     *
     * @return Eremuak
     */
    public function setDatuenbabesatext($datuenbabesatext)
    {
        $this->datuenbabesatext = $datuenbabesatext;

        return $this;
    }

    /**
     * Get datuenbabesatext
     *
     * @return boolean
     */
    public function getDatuenbabesatext()
    {
        return $this->datuenbabesatext;
    }

    /**
     * Set datuenbabesatable
     *
     * @param boolean $datuenbabesatable
     *
     * @return Eremuak
     */
    public function setDatuenbabesatable($datuenbabesatable)
    {
        $this->datuenbabesatable = $datuenbabesatable;

        return $this;
    }

    /**
     * Get datuenbabesatable
     *
     * @return boolean
     */
    public function getDatuenbabesatable()
    {
        return $this->datuenbabesatable;
    }

    /**
     * Set datuenbabesalabeleu
     *
     * @param string $datuenbabesalabeleu
     *
     * @return Eremuak
     */
    public function setDatuenbabesalabeleu($datuenbabesalabeleu)
    {
        $this->datuenbabesalabeleu = $datuenbabesalabeleu;

        return $this;
    }

    /**
     * Get datuenbabesalabeleu
     *
     * @return string
     */
    public function getDatuenbabesalabeleu()
    {
        return $this->datuenbabesalabeleu;
    }

    /**
     * Set datuenbabesalabeles
     *
     * @param string $datuenbabesalabeles
     *
     * @return Eremuak
     */
    public function setDatuenbabesalabeles($datuenbabesalabeles)
    {
        $this->datuenbabesalabeles = $datuenbabesalabeles;

        return $this;
    }

    /**
     * Get datuenbabesalabeles
     *
     * @return string
     */
    public function getDatuenbabesalabeles()
    {
        return $this->datuenbabesalabeles;
    }

    /**
     * Set norkebatzitext
     *
     * @param boolean $norkebatzitext
     *
     * @return Eremuak
     */
    public function setNorkebatzitext($norkebatzitext)
    {
        $this->norkebatzitext = $norkebatzitext;

        return $this;
    }

    /**
     * Get norkebatzitext
     *
     * @return boolean
     */
    public function getNorkebatzitext()
    {
        return $this->norkebatzitext;
    }

    /**
     * Set norkebatzitable
     *
     * @param boolean $norkebatzitable
     *
     * @return Eremuak
     */
    public function setNorkebatzitable($norkebatzitable)
    {
        $this->norkebatzitable = $norkebatzitable;

        return $this;
    }

    /**
     * Get norkebatzitable
     *
     * @return boolean
     */
    public function getNorkebatzitable()
    {
        return $this->norkebatzitable;
    }

    /**
     * Set norkebatzilabeleu
     *
     * @param string $norkebatzilabeleu
     *
     * @return Eremuak
     */
    public function setNorkebatzilabeleu($norkebatzilabeleu)
    {
        $this->norkebatzilabeleu = $norkebatzilabeleu;

        return $this;
    }

    /**
     * Get norkebatzilabeleu
     *
     * @return string
     */
    public function getNorkebatzilabeleu()
    {
        return $this->norkebatzilabeleu;
    }

    /**
     * Set norkebatzilabeles
     *
     * @param string $norkebatzilabeles
     *
     * @return Eremuak
     */
    public function setNorkebatzilabeles($norkebatzilabeles)
    {
        $this->norkebatzilabeles = $norkebatzilabeles;

        return $this;
    }

    /**
     * Get norkebatzilabeles
     *
     * @return string
     */
    public function getNorkebatzilabeles()
    {
        return $this->norkebatzilabeles;
    }

    /**
     * Set besteak1text
     *
     * @param boolean $besteak1text
     *
     * @return Eremuak
     */
    public function setBesteak1text($besteak1text)
    {
        $this->besteak1text = $besteak1text;

        return $this;
    }

    /**
     * Get besteak1text
     *
     * @return boolean
     */
    public function getBesteak1text()
    {
        return $this->besteak1text;
    }

    /**
     * Set besteak1table
     *
     * @param boolean $besteak1table
     *
     * @return Eremuak
     */
    public function setBesteak1table($besteak1table)
    {
        $this->besteak1table = $besteak1table;

        return $this;
    }

    /**
     * Get besteak1table
     *
     * @return boolean
     */
    public function getBesteak1table()
    {
        return $this->besteak1table;
    }

    /**
     * Set besteak1labeleu
     *
     * @param string $besteak1labeleu
     *
     * @return Eremuak
     */
    public function setBesteak1labeleu($besteak1labeleu)
    {
        $this->besteak1labeleu = $besteak1labeleu;

        return $this;
    }

    /**
     * Get besteak1labeleu
     *
     * @return string
     */
    public function getBesteak1labeleu()
    {
        return $this->besteak1labeleu;
    }

    /**
     * Set besteak1labeles
     *
     * @param string $besteak1labeles
     *
     * @return Eremuak
     */
    public function setBesteak1labeles($besteak1labeles)
    {
        $this->besteak1labeles = $besteak1labeles;

        return $this;
    }

    /**
     * Get besteak1labeles
     *
     * @return string
     */
    public function getBesteak1labeles()
    {
        return $this->besteak1labeles;
    }

    /**
     * Set besteak2text
     *
     * @param boolean $besteak2text
     *
     * @return Eremuak
     */
    public function setBesteak2text($besteak2text)
    {
        $this->besteak2text = $besteak2text;

        return $this;
    }

    /**
     * Get besteak2text
     *
     * @return boolean
     */
    public function getBesteak2text()
    {
        return $this->besteak2text;
    }

    /**
     * Set besteak2table
     *
     * @param boolean $besteak2table
     *
     * @return Eremuak
     */
    public function setBesteak2table($besteak2table)
    {
        $this->besteak2table = $besteak2table;

        return $this;
    }

    /**
     * Get besteak2table
     *
     * @return boolean
     */
    public function getBesteak2table()
    {
        return $this->besteak2table;
    }

    /**
     * Set besteak2labeleu
     *
     * @param string $besteak2labeleu
     *
     * @return Eremuak
     */
    public function setBesteak2labeleu($besteak2labeleu)
    {
        $this->besteak2labeleu = $besteak2labeleu;

        return $this;
    }

    /**
     * Get besteak2labeleu
     *
     * @return string
     */
    public function getBesteak2labeleu()
    {
        return $this->besteak2labeleu;
    }

    /**
     * Set besteak2labeles
     *
     * @param string $besteak2labeles
     *
     * @return Eremuak
     */
    public function setBesteak2labeles($besteak2labeles)
    {
        $this->besteak2labeles = $besteak2labeles;

        return $this;
    }

    /**
     * Get besteak2labeles
     *
     * @return string
     */
    public function getBesteak2labeles()
    {
        return $this->besteak2labeles;
    }

    /**
     * Set besteak3text
     *
     * @param boolean $besteak3text
     *
     * @return Eremuak
     */
    public function setBesteak3text($besteak3text)
    {
        $this->besteak3text = $besteak3text;

        return $this;
    }

    /**
     * Get besteak3text
     *
     * @return boolean
     */
    public function getBesteak3text()
    {
        return $this->besteak3text;
    }

    /**
     * Set besteak3table
     *
     * @param boolean $besteak3table
     *
     * @return Eremuak
     */
    public function setBesteak3table($besteak3table)
    {
        $this->besteak3table = $besteak3table;

        return $this;
    }

    /**
     * Get besteak3table
     *
     * @return boolean
     */
    public function getBesteak3table()
    {
        return $this->besteak3table;
    }

    /**
     * Set besteak3labeleu
     *
     * @param string $besteak3labeleu
     *
     * @return Eremuak
     */
    public function setBesteak3labeleu($besteak3labeleu)
    {
        $this->besteak3labeleu = $besteak3labeleu;

        return $this;
    }

    /**
     * Get besteak3labeleu
     *
     * @return string
     */
    public function getBesteak3labeleu()
    {
        return $this->besteak3labeleu;
    }

    /**
     * Set besteak3labeles
     *
     * @param string $besteak3labeles
     *
     * @return Eremuak
     */
    public function setBesteak3labeles($besteak3labeles)
    {
        $this->besteak3labeles = $besteak3labeles;

        return $this;
    }

    /**
     * Get besteak3labeles
     *
     * @return string
     */
    public function getBesteak3labeles()
    {
        return $this->besteak3labeles;
    }

    /**
     * Set kanalatext
     *
     * @param boolean $kanalatext
     *
     * @return Eremuak
     */
    public function setKanalatext($kanalatext)
    {
        $this->kanalatext = $kanalatext;

        return $this;
    }

    /**
     * Get kanalatext
     *
     * @return boolean
     */
    public function getKanalatext()
    {
        return $this->kanalatext;
    }

    /**
     * Set kanalatable
     *
     * @param boolean $kanalatable
     *
     * @return Eremuak
     */
    public function setKanalatable($kanalatable)
    {
        $this->kanalatable = $kanalatable;

        return $this;
    }

    /**
     * Get kanalatable
     *
     * @return boolean
     */
    public function getKanalatable()
    {
        return $this->kanalatable;
    }

    /**
     * Set kanalalabeleu
     *
     * @param string $kanalalabeleu
     *
     * @return Eremuak
     */
    public function setKanalalabeleu($kanalalabeleu)
    {
        $this->kanalalabeleu = $kanalalabeleu;

        return $this;
    }

    /**
     * Get kanalalabeleu
     *
     * @return string
     */
    public function getKanalalabeleu()
    {
        return $this->kanalalabeleu;
    }

    /**
     * Set kanalalabeles
     *
     * @param string $kanalalabeles
     *
     * @return Eremuak
     */
    public function setKanalalabeles($kanalalabeles)
    {
        $this->kanalalabeles = $kanalalabeles;

        return $this;
    }

    /**
     * Get kanalalabeles
     *
     * @return string
     */
    public function getKanalalabeles()
    {
        return $this->kanalalabeles;
    }

    /**
     * Set epealabeleu
     *
     * @param string $epealabeleu
     *
     * @return Eremuak
     */
    public function setEpealabeleu($epealabeleu)
    {
        $this->epealabeleu = $epealabeleu;

        return $this;
    }

    /**
     * Get epealabeleu
     *
     * @return string
     */
    public function getEpealabeleu()
    {
        return $this->epealabeleu;
    }

    /**
     * Set epealabeles
     *
     * @param string $epealabeles
     *
     * @return Eremuak
     */
    public function setEpealabeles($epealabeles)
    {
        $this->epealabeles = $epealabeles;

        return $this;
    }

    /**
     * Get epealabeles
     *
     * @return string
     */
    public function getEpealabeles()
    {
        return $this->epealabeles;
    }

    /**
     * Set doanlabeleu
     *
     * @param string $doanlabeleu
     *
     * @return Eremuak
     */
    public function setDoanlabeleu($doanlabeleu)
    {
        $this->doanlabeleu = $doanlabeleu;

        return $this;
    }

    /**
     * Get doanlabeleu
     *
     * @return string
     */
    public function getDoanlabeleu()
    {
        return $this->doanlabeleu;
    }

    /**
     * Set doanlabeles
     *
     * @param string $doanlabeles
     *
     * @return Eremuak
     */
    public function setDoanlabeles($doanlabeles)
    {
        $this->doanlabeles = $doanlabeles;

        return $this;
    }

    /**
     * Get doanlabeles
     *
     * @return string
     */
    public function getDoanlabeles()
    {
        return $this->doanlabeles;
    }

    /**
     * Set azpisailatable
     *
     * @param boolean $azpisailatable
     *
     * @return Eremuak
     */
    public function setAzpisailatable($azpisailatable)
    {
        $this->azpisailatable = $azpisailatable;

        return $this;
    }

    /**
     * Get azpisailatable
     *
     * @return boolean
     */
    public function getAzpisailatable()
    {
        return $this->azpisailatable;
    }

    /**
     * Set azpisailalabeleu
     *
     * @param string $azpisailalabeleu
     *
     * @return Eremuak
     */
    public function setAzpisailalabeleu($azpisailalabeleu)
    {
        $this->azpisailalabeleu = $azpisailalabeleu;

        return $this;
    }

    /**
     * Get azpisailalabeleu
     *
     * @return string
     */
    public function getAzpisailalabeleu()
    {
        return $this->azpisailalabeleu;
    }

    /**
     * Set azpisailalabeles
     *
     * @param string $azpisailalabeles
     *
     * @return Eremuak
     */
    public function setAzpisailalabeles($azpisailalabeles)
    {
        $this->azpisailalabeles = $azpisailalabeles;

        return $this;
    }

    /**
     * Get azpisailalabeles
     *
     * @return string
     */
    public function getAzpisailalabeles()
    {
        return $this->azpisailalabeles;
    }
}
