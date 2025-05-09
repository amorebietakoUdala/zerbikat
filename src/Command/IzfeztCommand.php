<?php

namespace App\Command;

use App\Entity\Eremuak;
use App\Entity\Kanalmota;
use App\Entity\Udala;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use App\Repository\EremuakRepository;
use App\Repository\FamiliaRepository;

#[AsCommand('api:izfezt','Zerbitzu telematikoen fitxategia sortu. Familia <-> Azpifamilia erabiliz')]
class IzfeztCommand extends Command
{

    protected $unekoFitxaKodea = "";

    public function __construct(
        protected EntityManagerInterface $em,
        private string $zzoo_aplikazioaren_API_url,
        private EremuakRepository $eremuakRepo,
        private FamiliaRepository $familiaRepo
        )
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->addArgument( 'udalKodea', InputArgument::REQUIRED, 'Udal kodea, adibidez pasaiarentzat 064.' )
            ->addArgument( 'debug', InputArgument::OPTIONAL, 'Informazio areagotua bistaratu.' );
    }

    function zerbikatParametroa( $param )
    {
        if ( !$param ) {
            return null;
        }
        switch ( $param ) {
            case "01":
                return "URG";
                break;
            case "0101":
                return "URA";
                break;
            case "02":
                return "UML";
                break;
            case "03":
                return "URB";
                break;
            case "04":
                return "UPF";
                break;
            case "05":
                return "UPM-PM";
                break;
            case "0501":
                return "UPM-PV";
                break;
            case "0502":
                return "UPM-HE";
                break;
            case "0503":
                return "UPM-CE";
                break;
            case "06":
                return "UEX";
                break;
            case "07":
                return "UVD";
                break;
            case "08":
                return $this->unekoFitxaKodea;
                break;
            case "0800":
                return $this->unekoFitxaKodea;
                break;
            case "0801":
                return $this->unekoFitxaKodea;
                break;
            case "0802":
                return $this->unekoFitxaKodea;
                break;
            case "0803":
                return $this->unekoFitxaKodea;
                break;
            case "09":
                return "URM";
                break;
            default:
                return $param;
                break;
        }
    }

    // UDAA20401
    function addOrria( $A204AYUNTA, $IdPagina, $denomi, $titcast, $titeus, $publicada, $tipo )
    {
        $denomi = str_replace( '\'', '"', $denomi );
        $titcast = str_replace( '\'', '"', $titcast );
        $titeus = str_replace( '\'', '"', $titeus );

        $A204IDPAGINA = $IdPagina;
        $A204DENOMI = "'Home " . $denomi . "'";
        $A204TITCAST = "'" . $titcast . "'";
        $A204TITEUSK = "'" . $titeus . "'";
        $A204PUBLICADA = $publicada;
        $A204FECALTA = null;
        $A204IDTIPO = "''";

        // Itzuplena egin, zerbikat-etik 0101 etortzen da, hori itzuli behar da IZFE-ko parametroetara
        $tipo = $this->zerbikatParametroa( $tipo );

        switch ( $tipo ) {
            case null:
                $A204TIPO = "'PROPIA'";
                break;

            case "USC":
                $A204TIPO = "'HOME'";
                $A204IDTIPO = "'USC'";
                break;

            case "UXX":
                $A204TIPO = "'PROPIA'";
                break;

            case "EXPEDIENTE":
                $A204TIPO = "'EXPEDIENTE'";
                $A204IDTIPO = "'" . $tipo . "'";
            default:
                $servicios = [
                    "UML",
                    "UPF",
                    "URM",
                    "UEX",
                    "UPM-PM",
                    "UPM-PV",
                    "UPM-CE",
                    "UPM-HE",
                    "URG",
                    //"URA",
                    "URB",
                    "UVD",
                ];

                if ( $tipo == "URA" ) {
                    $A204TIPO = "'FITXAZERBIKAT'";
                    $A204IDTIPO = "'" . $tipo . $IdPagina . "'";
                } elseif ( in_array( $tipo, $servicios ) ) {
                    $A204TIPO = "'SERVICIO'";
                    $A204IDTIPO = "'" . $tipo . "'";
                } else {
                    $A204TIPO = "'PROPIA'";
                    $A204IDTIPO = "'" . $tipo . "'";
                }
        }

        $sql = "INSERT INTO UDAA20401 (A204AYUNTA,A204IDPAGINA,A204DENOMI,A204TITCAST,A204TITEUSK,A204PUBLICADA,A204FECALTA,A204FECBAJA,A204TIPO,A204IDTIPO,A204CAPLI)
                              VALUES  ($A204AYUNTA, $A204IDPAGINA, $A204DENOMI, $A204TITCAST, $A204TITEUSK, $A204PUBLICADA, null, null, $A204TIPO, $A204IDTIPO, 'Z');\n";

        return $sql;
    }

    // UDAA20301
    function addBloque( $A204AYUNTA, $idBlokea, $tites, $titeus, $subtites = '', $subtiteus = '' )
    {
        $tites = str_replace( '\'', '"', $tites );
        $titeus = str_replace( '\'', '"', $titeus );
        $subtites = str_replace( '\'', '"', $subtites );
        $subtiteus = str_replace( '\'', '"', $subtiteus );

        if ( strlen( $subtites ) === 0 ) {
            $subtites = "''";
        }
        if ( strlen( $subtiteus ) === 0 ) {
            $subtiteus = "''";
        }
        $A203AYUNTA = $A204AYUNTA;
        $A203IDBLOQUE = $idBlokea;
        $A203DENOMI = "'" . $idBlokea . " Blokea'";
        $A203TITCAST = "'" . $tites . "'";
        $A203TITEUSK = "'" . $titeus . "'";
        if ( $subtites !== "''" ) {
            $subtites = "'" . $subtites . "'";
        }
        if ( $subtiteus !== "''" ) {
            $subtiteus = "'" . $subtiteus . "'";
        }

        $A203FECALTA = null;

        $sql = "INSERT INTO UDAA20301 (A203AYUNTA,A203IDBLOQUE,A203DENOMI,A203TITCAST,A203TITEUSK,A203FECALTA,A203FECBAJA,A203CAPLI,A203SUBTITCAST,A203SUBTITEUSK)
                VALUES($A203AYUNTA, $A203IDBLOQUE, $A203DENOMI, $A203TITCAST, $A203TITEUSK, null, null, 'Z', $subtites, $subtiteus);\n";

        return $sql;
    }

    // UDAA20601
    function addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden )
    {
        $A206AYUNTA = $A204AYUNTA;
        $A206IDPAGINA = $idPagina;
        $A206IDBLOQUE = $idBlokea;
        $A206ORDEN = $idOrden;
        $A206VISUAL = 0;

        $sql = "INSERT INTO UDAA20601 (A206AYUNTA,A206IDPAGINA,A206IDBLOQUE,A206ORDEN,A206VISUAL) 
                VALUES($A206AYUNTA, $A206IDPAGINA, $A206IDBLOQUE, $A206ORDEN, $A206VISUAL);\n";

        return $sql;
    }

    // UDAA20201
    function addElementua( $A204AYUNTA, $idElementua, $denomi, $titcast, $titeus, $servicio, $linkext = '' )
    {
        $denomi = str_replace( '\'', '"', $denomi );
        $titcast = str_replace( '&nbsp;', ' ', $titcast );
        $titcast = str_replace( '&nbsp', ' ', $titcast );
        $titeus = str_replace( '\'', '"', $titeus );
        $titcast = str_replace( '\'', '"', $titcast );
        $titeus = str_replace( '&nbsp;', ' ', $titeus );
        $titeus = str_replace( '&nbsp', ' ', $titeus );

        $A202AYUNTA = $A204AYUNTA;
        $A202IDLINEA = $idElementua;
        $A202DENOMI = "'" . $denomi . "'";
        $A202TEXCAST = "'" . $titcast . "'";
        $A202TEXEUSK = "'" . $titeus . "'";
        $A202FECALTA = null;
        $A202LINKEXT = "''";

        if ( $denomi !== "Texto" ) {
            // Itzuplena egin, zerbikat-etik 0101 etortzen da, hori itzuli behar da IZFE-ko parametroetara
            $tipo = $this->zerbikatParametroa( $servicio );

            switch ( $tipo ) {
                case null:
                    $A202SERVICIO = "'PROPIA'";
                    $A202LINKEXT = "'" . $linkext . "'";
                    break;

                case "PARRAFO":
                    $A202SERVICIO = "'PROPIA'";
                    $A202LINKEXT = "'" . $linkext . "'";
                    break;

                case "USC":
                    $A202SERVICIO = "'HOME'";
                    break;

                case "UXX":
                    $A202SERVICIO = "'PROPIA'";
                    $A202LINKEXT = "'" . $linkext . "'";
                    break;

                case "URM":
                    $A202SERVICIO = "'SERVICIO'";
                    $A202LINKEXT = "'" . $tipo . "'";
                    break;

                default:
                    $servicios = [
                        "UML",
                        "UPF",
                        "UEX",
                        "UPM-PM",
                        "UPM-PV",
                        "UPM-CE",
                        "UPM-HE",
                        "URG",
                        //"URA",
                        "URB",
                        "UVD",
                    ];

                    if ( $tipo == "URA" ) {
                        $A202SERVICIO = "'FITXAZERBIKAT'";
                        $A202LINKEXT = "'" . $tipo . $linkext . "'";
                    } elseif ( in_array( $tipo, $servicios ) ) {
                        $A202SERVICIO = "'SERVICIO'";
                        $A202LINKEXT = "'" . $tipo . "'";
                    } else {
                        $A202SERVICIO = "'EXPEDIENTE'";
                        $A202LINKEXT = "'" . $tipo . "'";
                    }
            }
        } else {
            $A202SERVICIO = "'PARRAFO'";
        }


        $sql = "INSERT INTO UDAA20201 (A202AYUNTA, A202IDLINEA, A202DENOMI, A202TEXCAST, A202TEXEUSK, A202LINKEXT, A202SERVICIO, A202FECALTA, A202FECBAJA, A202CAPLI)
               VALUES($A202AYUNTA, $A202IDLINEA, $A202DENOMI, $A202TEXCAST, $A202TEXEUSK, $A202LINKEXT, $A202SERVICIO, null, null, 'Z');\n";

        return $sql;
    }

    // UDAA20501
    function addElementuaBloque( $A204AYUNTA, $idBlokea, $idElementua, $idOrdenElementua )
    {
        $sql = "INSERT INTO UDAA20501 (A205AYUNTA,A205IDBLOQUE,A205IDLINEA,A205ORDEN)
            VALUES($A204AYUNTA, $idBlokea, $idElementua, $idOrdenElementua);\n";

        return $sql;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        $udalKodea = $input->getArgument( 'udalKodea' );
        $debug = $input->getArgument( 'debug' );

        $filename = "public/doc/$udalKodea/izfesql.sql";
        $udala = $this->em->getRepository( Udala::class )->findOneBy(
            ['kodea' => $udalKodea]
        );

        $output->writeln(
            [
                '',
                '<info>===============================</info>',
                'Aukeratutako udala => ' . $udala->getIzenaeu(),
                'Sortuko den fitxategia => ' . $filename,
                '<info>===============================</info>',
                '',
                '',
            ]
        );

        $query = $this->em->createQuery(
            '
                    SELECT f
                    FROM App:Fitxa f
                    WHERE f.publikoa=1 AND f.udala = :udalaid
                    ORDER BY f.espedientekodea ASC
                '
        )->setParameter( 'udalaid', $udala->getId() );
        $fitxak = $query->getResult();

        $helper = $this->getHelper( 'question' );
        $question = new ChoiceQuestion(
            'Datuak zuzenak dira? Horrela bada tauletako datuak ezabatuko dira.',
            ['Bai', 'Ez'],
            null
        );

        $color = $helper->ask( $input, $output, $question );

        if ( $color === 'Ez' ) {
            $output->writeln(
                [
                    '',
                    '<info>===============================</info>',
                    'Erabiltzaileak prozesua ezeztatu du.',
                    '<info>===============================</info>',
                    '',
                    '',
                ]
            );

            return Command::SUCCESS;
        }


        /** CONFIGURAZIOA */
        $COUNT_FITXA = count( $fitxak );
        $idPagina = 9000;
        $idBlokea = 9000;
        $idOrden = 1;
        $idOrdenElementua = 1;
        $idElementua = 9000;
        $A204AYUNTA = "'" . $udalKodea . "'";
        $mapa = [];
        $sortutakoAzpifamiliak = [];
        $sortutakoFitxak = [];

        $sql = "DELETE FROM UDAA20401 WHERE A204CAPLI='Z' AND A204AYUNTA=$A204AYUNTA;\n"; // Orriak
        $sql = $sql . "DELETE FROM UDAA20201 WHERE A202CAPLI='Z' AND A202AYUNTA=$A204AYUNTA;\n"; // Elementuak
        $sql = $sql . "DELETE FROM UDAA20301 WHERE A203CAPLI='Z' AND A203AYUNTA=$A204AYUNTA;\n"; // Blokeak
        $sql = $sql . "DELETE FROM UDAA20501 WHERE A205AYUNTA=$A204AYUNTA AND A205IDBLOQUE >= 9000 AND A205IDLINEA >=9000;\n"; // Blokeak - Elementuak
        $sql = $sql . "DELETE FROM UDAA20601 WHERE A206AYUNTA=$A204AYUNTA AND A206IDPAGINA >=9000 AND A206IDBLOQUE >=9000;\n"; // Orriak - Blokeak


        $output->writeln( [ $COUNT_FITXA . ' aurkitu dira.', '' ] );

        if ( !$debug ) {
            $progress = new ProgressBar( $output, $COUNT_FITXA );
            $progress->start();
        }


        $kanalmotak = $this->em->getRepository( Kanalmota::class )->findAll();

        // $query = $this->em->createQuery(
        //     '
        //             SELECT f.oharraktext,f.helburuatext,f.ebazpensinpli,f.arduraaitorpena,f.aurreikusi,f.arrunta,f.isiltasunadmin,f.norkeskatutext,f.norkeskatutable,f.dokumentazioatext,f.dokumentazioatable,f.kostuatext,f.kostuatable,f.araudiatext,f.araudiatable,f.prozeduratext,f.prozeduratable,f.doklaguntext,f.doklaguntable,f.datuenbabesatext,f.datuenbabesatable,f.norkebatzitext,f.norkebatzitable,f.besteak1text,f.besteak1table,f.besteak2text,f.besteak2table,f.besteak3text,f.besteak3table,f.kanalatext,f.kanalatable,f.azpisailatable
        //               FROM App:Eremuak f
        //               WHERE f.udala = :udala
        //         '
        // );
        // $query->setParameter( 'udala', $udala->getId() );
        // $eremuak = $query->getSingleResult();
        $eremuak = $this->eremuakRepo->findOneByUdalKodea($udala->getId());
        // $query = $this->em->createQuery(
        //     '
        //           SELECT f.oharraklabeleu,f.oharraklabeles,f.helburualabeleu,f.helburualabeles,f.ebazpensinplilabeleu,f.ebazpensinplilabeles,f.arduraaitorpenalabeleu,f.arduraaitorpenalabeles,f.aurreikusilabeleu,f.aurreikusilabeles,f.arruntalabeleu,f.arruntalabeles,f.isiltasunadminlabeleu,f.isiltasunadminlabeles,f.norkeskatulabeleu,f.norkeskatulabeles,f.dokumentazioalabeleu,f.dokumentazioalabeles,f.kostualabeleu,f.kostualabeles,f.araudialabeleu,f.araudialabeles,f.prozeduralabeleu,f.prozeduralabeles,f.doklagunlabeleu,f.doklagunlabeles,f.datuenbabesalabeleu,f.datuenbabesalabeles,f.norkebatzilabeleu,f.norkebatzilabeles,f.besteak1labeleu,f.besteak1labeles,f.besteak2labeleu,f.besteak2labeles,f.besteak3labeleu,f.besteak3labeles,f.kanalalabeleu,f.kanalalabeles,f.epealabeleu,f.epealabeles,f.doanlabeleu,f.doanlabeles,f.azpisailalabeleu,f.azpisailalabeles
        //             FROM App:Eremuak f
        //             WHERE f.udala = :udala
        //         '
        // );
        // $query->setParameter( 'udala', $udala->getId() );
        // $labelak = $query->getSingleResult();
        $labelak = $this->eremuakRepo->findLabelakByUdalKodea($udala->getId());
        // $query = $this->em->createQuery(
        //     '
        //           SELECT f 
        //             FROM App:Familia f 
        //               LEFT JOIN App:Udala u WITH f.udala=u.id                      
        //           WHERE u.kodea = :udala AND f.parent IS NULL 
        //           ORDER BY f.ordena ASC
        //         '
        // );
        // $query->setParameter( 'udala', $udalKodea );
        // $familiak = $query->getResult();
        $familiak = $this->familiaRepo->findFamiliaByUdala($udalKodea);
        /*******************************************************************/
        /**** Home-a sortu  ************************************************/
        /*******************************************************************/
        $sql = $sql . $this->addOrria(
                $A204AYUNTA,
                $idPagina,
                'Home ' . $udala->getIzenaeu(),
                $udala->getIzenaes(),
                $udala->getIzenaeu(),
                1,
                'USC'
            );
        $idPaginaHome = $idPagina;
        $idPagina += 1;

        /*******************************************************************/
        /**** Fin home-a sortu  ********************************************/
        /*******************************************************************/


        /*******************************************************************/
        /**** Home-an familiak sortu  **************************************/
        /*******************************************************************/
        /** @var Familia $familia */
        foreach ( $familiak as $familia ) {

            $sql = $sql . $this->addBloque(
                    $A204AYUNTA,
                    $idBlokea,
                    $familia->getFamiliaes(),
                    $familia->getFamiliaeu(),
                    $familia->getDeskribapenaes(),
                    $familia->getDeskribapenaeu()
                );
            $familiarenBloquea = $idBlokea;
            $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPaginaHome, $idBlokea, $idOrden );

            $mapa[ $familia->getId() ] = $idBlokea;

            $idBlokea += 1;
            $idOrden += 1;
        }
        /*******************************************************************/
        /**** Fin home-an familiak sortu  **********************************/
        /*******************************************************************/

        foreach ( $familiak as $familia ) {
            if ( $debug ) {
                echo "\n";
                echo "\n";
                echo "\n";
                echo $familia . "\n";
                echo "-----------------------------------------------\n";
            }
            foreach ( $familia->getFitxafamilia() as $fitxafamilia ) {
                if ( $debug ) {
                    echo "|__" . $fitxafamilia->getFitxa() . "\n";
                }
                /** @var Fitxa $fitxa */
                $fitxa = $fitxafamilia->getFitxa();
                $this->unekoFitxaKodea = $fitxa->getExpedientes();
                //$mapa[$familia->getId()] = $idBlokea;


                /**************************************************************************************************/
                /**** Fitxak-a sortu   ****************************************************************************/
                /**** addFitxa()       ****************************************************************************/
                /**************************************************************************************************/
                if ( 1 == 1 ) { // Folding egiteko addFitxa bezala refaktorizatu beharko zen

                    if ( !in_array(
                        $fitxa->getEspedientekodea(),
                        $sortutakoFitxak
                    )
                    ) { // Begiratu ea aldez aurretik sortu dugun...
                        $kostuZerrenda = [];
                        foreach ( $fitxa->getKostuak() as $kostu ) {
                            if ( ( strlen( $this->zzoo_aplikazioaren_API_url ) > 0 ) && ( $kostu->getKostua() ) ) {
                                $client = new GuzzleHttp\Client();
                                $proba = $client->request(
                                    'GET',
                                    $this->zzoo_aplikazioaren_API_url . '/zerga/' . $kostu->getKostua() . '?format=json'
                                );
                                $fitxaKostua = (string)$proba->getBody();
                                $array = json_decode( $fitxaKostua, true );
                                $kostuZerrenda[] = $array;
                            }
                        }

                        // Orria sortu fitxarentzat
                        $sql = $sql . $this->addOrria(
                                $A204AYUNTA,
                                $idPagina,
                                $fitxa->getEspedientekodea(),
                                $fitxa->getDeskribapenaes(),
                                $fitxa->getDeskribapenaeu(),
                                1,
                                $fitxa->getParametroa()
                            );

                        $sortutakoFitxak[ $fitxa->getEspedientekodea() ] = $idPagina;

                        /* FITXAREN ERAMUAK GEHITU */
                        /****** HASI HELBURUA *********************************************************************/
                        if ( $eremuak[ 'helburuatext' ] ) {
                            $sql = $sql . $this->addBloque(
                                    $A204AYUNTA,
                                    $idBlokea,
                                    $labelak[ 'helburualabeles' ],
                                    $labelak[ 'helburualabeleu' ]
                                );
                            $sql = $sql . $this->addOrriaBloque(
                                    $A204AYUNTA,
                                    $idPagina,
                                    $idBlokea,
                                    $idOrden
                                );
                            $sql = $sql . $this->addElementua(
                                    $A204AYUNTA,
                                    $idElementua,
                                    "Texto",
                                    $fitxa->getHelburuaes() . "<span id='kodea' style='display:none;'>" . $fitxa->getEspedientekodea() . "</span>",
                                    $fitxa->getHelburuaeu() . "<span id='kodea' style='display:none;'>" . $fitxa->getEspedientekodea() . "</span>",
                                    "PARRAFO"
                                );
                            $sql = $sql . $this->addElementuaBloque(
                                    $A204AYUNTA,
                                    $idBlokea,
                                    $idElementua,
                                    $idOrdenElementua
                                );
                            $idBlokea += 1;
                            $idOrden += 1;
                            $idOrdenElementua += 1;
                            $idElementua += 1;
                        }
                        /****** FIN HELBURUA *********************************************************************/

                        /****** HASI NORK ESKATU *********************************************************************/
                        if ( ( $eremuak[ 'norkeskatutext' ] ) || ( $eremuak[ 'norkeskatutable' ] ) ) {
                            $sql = $sql . $this->addBloque(
                                    $A204AYUNTA,
                                    $idBlokea,
                                    $labelak[ 'norkeskatulabeles' ],
                                    $labelak[ 'norkeskatulabeleu' ]
                                );
                            $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                            $badu = 0;
                            if ( $eremuak[ 'norkeskatutext' ] ) {

                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        $fitxa->getNorkes(),
                                        $fitxa->getNorkeu(),
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $badu = 1;
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                            }
                            if ( $eremuak[ "norkeskatutable" ] ) {
                                if ( $fitxa->getNorkeskatuak() ) {
                                    foreach ( $fitxa->getNorkeskatuak() as $nork ) {
                                        $sql = $sql . $this->addElementua(
                                                $A204AYUNTA,
                                                $idElementua,
                                                "Texto",
                                                $nork->getNorkes(),
                                                $nork->getNorkeu(),
                                                "PARRAFO"
                                            );
                                        $sql = $sql . $this->addElementuaBloque(
                                                $A204AYUNTA,
                                                $idBlokea,
                                                $idElementua,
                                                $idOrdenElementua
                                            );
                                        $idElementua += 1;
                                        $idOrdenElementua += 1;
                                        $badu = 1;
                                    }
                                }
                            }
                            if ( $badu == 0 ) {
                                // Ez dagokio
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        "No corresponde",
                                        "Ez dagokio",
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 0;
                            }
                            $idBlokea += 1;
                            $idOrden += 1;
                        }
                        /****** FIN NORK ESKATU *********************************************************************/

                        /****** HASI DOKUMENTAZIOA *********************************************************************/
                        if ( ( $eremuak[ 'dokumentazioatext' ] ) || ( $eremuak[ 'dokumentazioatable' ] ) ) {
                            $sql = $sql . $this->addBloque(
                                    $A204AYUNTA,
                                    $idBlokea,
                                    $labelak[ 'dokumentazioalabeles' ],
                                    $labelak[ 'dokumentazioalabeleu' ]
                                );
                            $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                            $badu = 0;
                            if ( $eremuak[ "dokumentazioatable" ] ) {
                                if ( $fitxa->getDokumentazioak() ) {
                                    $doctextes = "<ul>";
                                    $doctexteu = "<ul>";
                                    foreach ( $fitxa->getDokumentazioak() as $doc ) {
                                        $doctextes = $doctextes . "<li>";
                                        $doctexteu = $doctexteu . "<li>";

                                        if ( $doc->getEstekaeu() ) {
                                            $doctextes = $doctextes . "<a href='" . $doc->getEstekaes() . "' target='_blank'>" . $doc->getKodea() . " " . $doc->getDeskribapenaes() . "</a>";
                                            $doctexteu = $doctexteu . "<a href='" . $doc->getEstekaeu() . "' target='_blank'>" . $doc->getKodea() . " " . $doc->getDeskribapenaeu() . "</a>";
                                        } else {
                                            $doctextes = $doctextes . $doc->getKodea() . " " . $doc->getDeskribapenaes();
                                            $doctexteu = $doctextes . $doc->getKodea() . " " . $doc->getDeskribapenaeu();
                                        }
                                        $doctextes = $doctextes . "</li>";
                                        $doctexteu = $doctexteu . "</li>";
                                        $badu = 1;
                                    }
                                    $doctextes = $doctextes . "</ul>";
                                    $doctexteu = $doctexteu . "</ul>";

                                    if ( $badu == 1 ) {
                                        $sql = $sql . $this->addElementua(
                                                $A204AYUNTA,
                                                $idElementua,
                                                "Texto",
                                                $doctextes,
                                                $doctexteu,
                                                "PARRAFO"
                                            );
                                        $sql = $sql . $this->addElementuaBloque(
                                                $A204AYUNTA,
                                                $idBlokea,
                                                $idElementua,
                                                $idOrdenElementua
                                            );
                                        $idElementua += 1;
                                        $idOrdenElementua += 1;
                                    }
                                }
                            }
                            if ( $eremuak[ 'dokumentazioatext' ] ) {
                                if ( ( $fitxa->getDokumentazioaes() !== null ) || ( $fitxa->getDokumentazioaeu() !== null )
                                ) {
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            $fitxa->getDokumentazioaes(),
                                            $fitxa->getDokumentazioaeu(),
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 1;
                                }
                            }
                            if ( $badu == 0 ) {
                                // Ez dagokio
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        "No corresponde",
                                        "Ez dagokio",
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 0;
                            }
                            $idBlokea += 1;
                            $idOrden += 1;
                        }
                        /****** FIN DOKUMENTAZIOA *********************************************************************/

                        /****** HASI DOKUMENTAZIO LAGUNGARRIA *********************************************************************/
                        if ( ( $eremuak[ 'doklaguntext' ] ) || ( $eremuak[ 'doklaguntable' ] ) ) {
                            $sql = $sql . $this->addBloque(
                                    $A204AYUNTA,
                                    $idBlokea,
                                    $labelak[ 'dokumentazioalabeles' ],
                                    $labelak[ 'dokumentazioalabeleu' ]
                                );
                            $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                            $badu = 0;
                            if ( $eremuak[ 'doklaguntext' ] ) {
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        $fitxa->getDoklagunes(),
                                        $fitxa->getDoklaguneu(),
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 1;
                            }

                            if ( $eremuak[ "doklaguntable" ] ) {
                                if ( $fitxa->doklagunak() ) {
                                    $doctextes = "<ul>";
                                    $doctexteu = "<ul>";
                                    foreach ( $fitxa->doklagunak() as $doc ) {
                                        $doctextes = $doctextes . "<li>";
                                        $doctexteu = $doctexteu . "<li>";

                                        if ( $doc->getEstekaeu() ) {
                                            $doctextes = "<a href='" . $doc->getEstekaes() . "' target='_blank'>" . $doc->getKodea() . " " . $doc->getDeskribapenaes() . "</a>";
                                            $doctexteu = "<a href='" . $doc->getEstekaeu() . "' target='_blank'>" . $doc->getKodea() . " " . $doc->getDeskribapenaeu() . "</a>";
                                        } else {
                                            $doctextes = $doc->getKodea() . " " . $doc->getDeskribapenaes();
                                            $doctexteu = $doc->getKodea() . " " . $doc->getDeskribapenaeu();
                                        }
                                        $doctextes = $doctextes . "</li>";
                                        $doctexteu = $doctexteu . "</li>";
                                        $badu = 1;
                                    }

                                    if ( $badu == 1 ) {
                                        $sql = $sql . $this->addElementua(
                                                $A204AYUNTA,
                                                $idElementua,
                                                "Texto",
                                                $doctextes,
                                                $doctexteu,
                                                "PARRAFO"
                                            );
                                        $sql = $sql . $this->addElementuaBloque(
                                                $A204AYUNTA,
                                                $idBlokea,
                                                $idElementua,
                                                $idOrdenElementua
                                            );
                                        $idElementua += 1;
                                        $idOrdenElementua += 1;
                                    }
                                }
                            }
                            if ( $badu == 0 ) {
                                // Ez dagokio
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        "No corresponde",
                                        "Ez dagokio",
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 0;
                            }
                            $idBlokea += 1;
                            $idOrden += 1;
                        }
                        /****** FIN DOKUMENTAZIO LAGUNGARRIA *********************************************************************/

                        /****** HASI KANALA *********************************************************************/
                        if ( ( $eremuak[ 'kanalatext' ] ) || ( $eremuak[ 'kanalatable' ] ) ) {
                            $sql = $sql . $this->addBloque(
                                    $A204AYUNTA,
                                    $idBlokea,
                                    $labelak[ 'kanalalabeles' ],
                                    $labelak[ 'kanalalabeleu' ]
                                );
                            $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                            $badu = 0;
                            if ( $eremuak[ "kanalatable" ] ) {

                                foreach ( $kanalmotak as $k ) {
                                    $aurkitua = 0;
                                    $textes = "<ul>";
                                    $texteu = "<ul>";

                                    /** @var Kanala $kanala */
                                    foreach ( $fitxa->getKanalak() as $kanala ) {
                                        if ( ( $kanala->getKanalmota() == $k ) && ( $kanala->getErakutsi() == 1 ) ) {
                                            if ( $aurkitua == 0 ) {
                                                if ( $k->getIkonoa() ) {
                                                    $textes = $textes . "<i class='fa " . $k->getIkonoa() . " aria-hidden='true'></i>";
                                                    $texteu = $texteu . "<i class='fa " . $k->getIkonoa() . " aria-hidden='true'></i>";
                                                }
                                                $textes = $textes . "<b>" . $k->getMotaes() . ":</b>";
                                                $texteu = $texteu . "<b>" . $k->getMotaeu() . ":</b>";
                                                $aurkitua = 1;
                                            }
                                            if ( $kanala->getTelematikoa() ) {
                                                if ( $fitxa->getZerbitzua() ) {
                                                    if (( strlen($fitxa->getExpedientes()) > 0 )  && ( str_starts_with($fitxa->getParametroa(), "08") )) {
                                                        $textes = $textes . "<li><a href='" . $fitxa->getZerbitzua()->getErroaes() . $fitxa->getUdala()->getKodea() . $fitxa->getParametroa() . $fitxa->getExpedientes() . "' target='_blank'>" . $kanala->getIzenaes() . "</a></li>";
                                                        $texteu = $texteu . "<li><a href='" . $fitxa->getZerbitzua()->getErroaeu() . $fitxa->getUdala()->getKodea() . $fitxa->getParametroa() . $fitxa->getExpedientes() . "' target='_blank'>" . $kanala->getIzenaeu() . "</a></li>";
                                                    } else {
                                                        $textes = $textes . "<li><a href='" . $fitxa->getZerbitzua()->getErroaes() . $fitxa->getUdala()->getKodea() . $fitxa->getParametroa() . "' target='_blank'>" . $kanala->getIzenaes() . "</a></li>";
                                                        $texteu = $texteu . "<li><a href='" . $fitxa->getZerbitzua()->getErroaeu() . $fitxa->getUdala()->getKodea() . $fitxa->getParametroa() . "' target='_blank'>" . $kanala->getIzenaeu() . "</a></li>";
                                                    }
                                                }
                                            } else {
                                                if ( $k->getEsteka() ) {
                                                    $textes = $textes . "<li>";
                                                    $texteu = $texteu . "<li>";
                                                    if ( $kanala->getIzenaes() ) {
                                                        if ( ( preg_match(
                                                                '/@/',
                                                                $kanala->getEstekaes()
                                                            ) ) && ( !preg_match( '/maps/', $kanala->getEstekaes() ) )
                                                        ) {
                                                            $textes = $textes . "<a href='mailto:" . $kanala->getEstekaes() . "'>" . $kanala->getIzenaes() . "</a><br />";
                                                            $texteu = $texteu . "<a href='mailto:" . $kanala->getEstekaeu() . "'>" . $kanala->getIzenaeu() . "</a><br />";
                                                        } else {
                                                            $textes = $textes . "<a href='" . $kanala->getEstekaes() . "' target='_blank'>" . $kanala->getIzenaes() . "</a><br />";
                                                            $texteu = $texteu . "<a href='" . $kanala->getEstekaeu() . "' target='_blank'>" . $kanala->getIzenaeu() . "</a><br />";
                                                        }
                                                    }
                                                    if ( $kanala->getEraikina() ) {
                                                        $textes = $textes . $kanala->getEraikina()->getIzena() . "<br />";
                                                        $texteu = $texteu . $kanala->getEraikina()->getIzena() . "<br />";
                                                    }
                                                    if ( $kanala->getKalea() ) {
                                                        $textes = $textes . $kanala->getKalea() . " ";
                                                        $texteu = $texteu . $kanala->getKalea() . " ";
                                                    }
                                                    if ( $kanala->getKalezbkia() ) {
                                                        $textes = $textes . $kanala->getKalezbkia() . " ";
                                                        $texteu = $texteu . $kanala->getKalezbkia() . " ";
                                                    }
                                                    if ( $kanala->getPostakodea() ) {
                                                        $textes = $textes . $kanala->getPostakodea() . " ";
                                                        $texteu = $texteu . $kanala->getPostakodea() . " ";
                                                    }
                                                    if ( $kanala->getUdala() ) {
                                                        $textes = $textes . $kanala->getUdala()->getIzenaes() . "<br/>";
                                                        $texteu = $texteu . $kanala->getUdala()->getIzenaeu() . "<br/>";
                                                    }
                                                    if ( $kanala->getOrdutegia() ) {
                                                        $textes = $textes . $kanala->getOrdutegia() . "<br/>";
                                                        $texteu = $texteu . $kanala->getOrdutegia() . "<br/>";
                                                    }
                                                    if ( $kanala->getTelefonoa() ) {
                                                        $textes = $textes . $kanala->getTelefonoa() . "<br/>";
                                                        $texteu = $texteu . $kanala->getTelefonoa() . "<br/>";
                                                    }
                                                    if ( $kanala->getFax() ) {
                                                        $textes = $textes . $kanala->getFax() . "<br/>";
                                                        $texteu = $texteu . $kanala->getFax() . "<br/>";
                                                    }
                                                    $textes = $textes . "</li>";
                                                    $texteu = $texteu . "</li>";
                                                } else { // if ($k->getEsteka())
                                                    $textes = $textes . "<li>";
                                                    $texteu = $texteu . "<li>";
                                                    if ( $kanala->getIzenaes() ) {
                                                        $textes = $textes . $kanala->getIzenaes() . "<br/>";
                                                        $texteu = $texteu . $kanala->getIzenaes() . "<br/>";
                                                    }
                                                    if ( $kanala->getKalea() ) {
                                                        $textes = $textes . $kanala->getKalea() . " ";
                                                        $texteu = $texteu . $kanala->getKalea() . " ";
                                                    }
                                                    if ( $kanala->getKalezbkia() ) {
                                                        $textes = $textes . $kanala->getKalezbkia() . " ";
                                                        $texteu = $texteu . $kanala->getKalezbkia() . " ";
                                                    }
                                                    if ( $kanala->getPostakodea() ) {
                                                        $textes = $textes . $kanala->getPostakodea() . " ";
                                                        $texteu = $texteu . $kanala->getPostakodea() . " ";
                                                    }
                                                    if ( $kanala->getUdala() ) {
                                                        $textes = $textes . $kanala->getUdala()->getIzenaes() . "<br/>";
                                                        $texteu = $texteu . $kanala->getUdala()->getIzenaeu() . "<br/>";
                                                    }
                                                    if ( $kanala->getOrdutegia() ) {
                                                        $textes = $textes . $kanala->getOrdutegia() . "<br/>";
                                                        $texteu = $texteu . $kanala->getOrdutegia() . "<br/>";
                                                    }
                                                    if ( $kanala->getTelefonoa() ) {
                                                        $textes = $textes . $kanala->getTelefonoa() . "<br/>";
                                                        $texteu = $texteu . $kanala->getTelefonoa() . "<br/>";
                                                    }
                                                    if ( $kanala->getFax() ) {
                                                        $textes = $textes . $kanala->getFax() . "<br/>";
                                                        $texteu = $texteu . $kanala->getFax() . "<br/>";
                                                    }
                                                    $textes = $textes . "</li>";
                                                    $texteu = $texteu . "</li>";
                                                }
                                            }
                                            $badu = 1;
                                        }
                                    }
                                    $textes = $textes . "</ul>";
                                    $texteu = $texteu . "</ul>";
                                    if ( $badu == 1 ) {
                                        $sql = $sql . $this->addElementua(
                                                $A204AYUNTA,
                                                $idElementua,
                                                "Texto",
                                                $textes,
                                                $texteu,
                                                "PARRAFO"
                                            );
                                        $sql = $sql . $this->addElementuaBloque(
                                                $A204AYUNTA,
                                                $idBlokea,
                                                $idElementua,
                                                $idOrdenElementua
                                            );
                                        $idElementua += 1;
                                        $idOrdenElementua += 1;
                                    }
                                }
                            }
                            if ( $eremuak[ 'kanalatext' ] ) {
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        $fitxa->getKanalaes(),
                                        $fitxa->getKanalaeu(),
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 1;
                            }
                            if ( $badu == 0 ) {
                                // Ez dagokio
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        "No corresponde",
                                        "Ez dagokio",
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 0;
                            }
                            $idBlokea += 1;
                            $idOrden += 1;
                        }
                        /****** FIN DOKUMENTAZIOA *********************************************************************/

                        /****** HASI KOSTUA *********************************************************************/
                        if ( ( $eremuak[ 'kostuatext' ] ) || ( $eremuak[ 'kostuatable' ] ) ) {
                            $sql = $sql . $this->addBloque(
                                    $A204AYUNTA,
                                    $idBlokea,
                                    $labelak[ 'kostualabeles' ],
                                    $labelak[ 'kostualabeleu' ]
                                );
                            $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                            $kont = 0;
                            $textes = "";
                            $texteu = "";
                            $badu = 0;

                            if ( $eremuak[ "kostuatable" ] ) {
                                if ( $fitxa->getUdala()->getZergaor() ) {
                                    foreach ( $kostuZerrenda as $kostutaula ) {
                                        if ( $kostutaula !== null ) {
                                            $textes = $textes . "<table  class='table table-bordered table-condensed table-hover'><tr><th colspan='2' class='text-center'>" . $kostutaula[ "kodea_prod" ] . " - " . $kostutaula[ "izenburuaes_prod" ] . "</th></tr>";
                                            $texteu = $texteu . "<table  class='table table-bordered table-condensed table-hover'><tr><th colspan='2' class='text-center'>" . $kostutaula[ "kodea_prod" ] . " - " . $kostutaula[ "izenburuaeu_prod" ] . "</th></tr>";

                                            foreach ( $kostutaula[ "parrafoak" ] as $parrafo ) {
                                                if ( array_key_exists( "kontzeptuaes_prod", $parrafo ) ) {
                                                    $textes = $textes . "<tr><td colspan='2'>" . $parrafo[ "kontzeptuaes_prod" ] . "</td></tr>";
                                                    $texteu = $texteu . "<tr><td colspan='2'>" . $parrafo[ "kontzeptuaeu_prod" ] . "</td></tr>";
                                                }
                                            }
                                            foreach ( $kostutaula[ "kontzeptuak" ] as $kontzeptu ) {
                                                if ( array_key_exists( "kopurua_prod", $kontzeptu ) ) {
                                                    if ( array_key_exists( "unitatea_prod", $kontzeptu ) ) {
                                                        $textes = $textes . "<tr><td>" . $kontzeptu[ "kontzeptuaes_prod" ] . "</td><td NOWRAP>" . $kontzeptu[ "kopurua_prod" ] . " " . $kontzeptu[ "unitatea_prod" ] . "</td></tr>";
                                                        $texteu = $texteu . "<tr><td>" . $kontzeptu[ "kontzeptuaeu_prod" ] . "</td><td NOWRAP>" . $kontzeptu[ "kopurua_prod" ] . " " . $kontzeptu[ "unitatea_prod" ] . "</td></tr>";
                                                    } else {
                                                        $textes = $textes . "<tr><td>" . $kontzeptu[ "kontzeptuaes_prod" ] . "</td><td NOWRAP>" . $kontzeptu[ "kopurua_prod" ] . "</td></tr>";
                                                        $texteu = $texteu . "<tr><td>" . $kontzeptu[ "kontzeptuaeu_prod" ] . "</td><td NOWRAP>" . $kontzeptu[ "kopurua_prod" ] . "</td></tr>";
                                                    }
                                                }
                                            }
                                            $textes = $textes . "</table><br/>";
                                            $texteu = $texteu . "</table><br/>";

                                            $kont += 1;
                                            $badu = 1;
                                        }
                                    }
                                } else {
                                    foreach ( $fitxa->getAzpiatalak() as $azpiatal ) {
                                        $textes = $textes . "<table class='table table-bordered table-condensed table-hover'><tr><th colspan=2><a href='http://zergaordenantzak/kudeaketa.php/atala/show/id/" . $azpiatal->getId() . "' target='_blank'>" . $azpiatal->getKodea() . " - " . $azpiatal->getIzenburuaes() . "</a></th></tr>";
                                        $texteu = $texteu . "<table class='table table-bordered table-condensed table-hover'><tr><th colspan=2><a href='http://zergaordenantzak/kudeaketa.php/atala/show/id/" . $azpiatal->getId() . "' target='_blank'>" . $azpiatal->getKodea() . " - " . $azpiatal->getIzenburuaeu() . "</a></th></tr>";

                                        foreach ( $azpiatal->getParrafoak() as $parrafo ) {
                                            $textes = $textes . "<tr><td colspan='2'>" . $parrafo->getTestuaes() . "</td></tr>";
                                            $texteu = $texteu . "<tr><td colspan='2'>" . $parrafo->getTestuaeu() . "</td></tr>";
                                        }

                                        foreach ( $azpiatal->getKontzeptuak() as $kontzeptu ) {
                                            $textes = $textes . "<tr><td>" . $kontzeptu->getKontzeptuaes();
                                            if ( $kontzeptu->getBaldintza() ) {
                                                $textes = $textes . $kontzeptu->getBaldintza()->getBaldintzaes();
                                            }
                                            $textes = $textes . "</td><td>" . $kontzeptu->getKopurua() . " " . $kontzeptu->getUnitatea() . "</td></tr>";

                                            $texteu = $texteu . "<tr><td>" . $kontzeptu->getKontzeptuaeu();
                                            if ( $kontzeptu->getBaldintza() ) {
                                                $texteu = $texteu . $kontzeptu->getBaldintza()->getBaldintzaeu();
                                            }
                                            $texteu = $texteu . "</td><td>" . $kontzeptu->getKopurua() . " " . $kontzeptu->getUnitatea() . "</td></tr>";
                                        }
                                        $textes = $textes . "</table><br/>";
                                        $texteu = $texteu . "</table><br/>";
                                        $kont += 1;
                                        $badu = 1;
                                    }
                                }

                                if ( $badu == 1 ) {
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            $textes,
                                            $texteu,
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                }
                            }
                            if ( $eremuak[ 'kostuatext' ] ) {
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        $fitxa->getKostuaes(),
                                        $fitxa->getKostuaeu(),
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 1;
                            }

                            if ( ( ( $fitxa->getKostuaes() == null ) && ( $kont == 0 ) ) || ( ( $fitxa->getKostuaeu() == null ) && ( $kont == 0 ) )
                            ) {
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        $labelak[ "doanlabeles" ],
                                        $labelak[ "doanlabeleu" ],
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 1;
                            }
                            if ( $badu == 0 ) {
                                // Ez dagokio
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        "No corresponde",
                                        "Ez dagokio",
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 0;
                            }
                            $idBlokea += 1;
                            $idOrden += 1;
                        }
                        /****** FIN KOSTUA *********************************************************************/

                        /****** HASI EBAZPENA *********************************************************************/
                        if ( $eremuak[ 'ebazpensinpli' ] || ( $eremuak[ 'arduraaitorpena' ] ) || ( $eremuak[ 'aurreikusi' ] ) || ( $eremuak[ 'arrunta' ] ) || ( $eremuak[ 'isiltasunadmin' ] ) ) {
                            $sql = $sql . $this->addBloque(
                                    $A204AYUNTA,
                                    $idBlokea,
                                    $labelak[ 'epealabeles' ],
                                    $labelak[ 'epealabeleu' ]
                                );
                            $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                            $textes = "<ul>";
                            $texteu = "<ul>";

                            $badu = 0;
                            if ( $eremuak[ "aurreikusi" ] ) {
                                if ( $fitxa->getAurreikusi() ) {
                                    $textes = $textes . "<li>";
                                    $texteu = $texteu . "<li>";
                                    $textes = $textes . $labelak[ "aurreikusilabeles" ] . ": " . $fitxa->getAurreikusi() . "\n";
                                    $texteu = $texteu . $labelak[ "aurreikusilabeleu" ] . ": " . $fitxa->getAurreikusi() . "\n";
                                    $textes = $textes . "</li>";
                                    $texteu = $texteu . "</li>";
                                    $badu = 1;
                                }

                            }

                            if ( $eremuak[ "arrunta" ] ) {
                                if ( $fitxa->getArrunta() ) {
                                    $textes = $textes . "<li>";
                                    $texteu = $texteu . "<li>";
                                    $textes = $textes . $labelak[ "arruntalabeles" ] . ": " . $fitxa->getArrunta() . "\n";
                                    $texteu = $texteu . $labelak[ "arruntalabeleu" ] . ": " . $fitxa->getArrunta() . "\n";
                                    $textes = $textes . "</li>";
                                    $texteu = $texteu . "</li>";
                                    $badu = 1;
                                }
                            }

                            if ( $eremuak[ "ebazpensinpli" ] ) {
                                if ( $fitxa->getEbazpensinpli() ) {
                                    $textes = $textes . "<li>";
                                    $texteu = $texteu . "<li>";
                                    $textes = $textes . $labelak[ "ebazpensinplilabeles" ] . ": " . $fitxa->getEbazpensinpli() . "<br/>" . "\n";
                                    $texteu = $texteu . $labelak[ "ebazpensinplilabeleu" ] . ": " . $fitxa->getEbazpensinpli() . "<br/>" . "\n";
                                    $textes = $textes . "</li>";
                                    $texteu = $texteu . "</li>";
                                    $badu = 1;
                                }
                            }

                            if ( $eremuak[ "arduraaitorpena" ] ) {
                                if ( $fitxa->getArduraaitorpena() ) {
                                    $textes = $textes . "<li>";
                                    $texteu = $texteu . "<li>";
                                    $textes = $textes . $labelak[ "arduraaitorpenalabeles" ] . ": Si <br/>" . "\n";
                                    $texteu = $texteu . $labelak[ "arduraaitorpenalabeleu" ] . ": Bai <br/>" . "\n";
                                    $textes = $textes . "</li>";
                                    $texteu = $texteu . "</li>";
                                    $badu = 1;
                                }
                            }

                            if ( $eremuak[ "isiltasunadmin" ] ) {
                                if ( $fitxa->getIsiltasunadmin() ) {
                                    $textes = $textes . "<li>";
                                    $texteu = $texteu . "<li>";
                                    $textes = $textes . $labelak[ "isiltasunadminlabeles" ] . ": " . $fitxa->getIsiltasunadmin() . "<br/>" . "\n";
                                    $texteu = $texteu . $labelak[ "isiltasunadminlabeleu" ] . ": " . $fitxa->getIsiltasunadmin() . "<br/>" . "\n";
                                    $textes = $textes . "</li>";
                                    $texteu = $texteu . "</li>";
                                    $badu = 1;
                                }
                            }

                            $textes = $textes . "</ul>";
                            $texteu = $texteu . "</ul>";

                            if ( $badu == 1 ) {
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        $textes,
                                        $texteu,
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idOrdenElementua += 1;
                                $idElementua += 1;
                            }
                            $idBlokea += 1;
                            $idOrden += 1;
                        }
                        /****** FIN EBAZPENA*********************************************************************/

                        /****** HASI ARAUDIA *********************************************************************/
                        if ( ( $eremuak[ 'araudiatext' ] ) || ( $eremuak[ 'araudiatable' ] ) ) {
                            $sql = $sql . $this->addBloque(
                                    $A204AYUNTA,
                                    $idBlokea,
                                    $labelak[ 'araudialabeles' ],
                                    $labelak[ 'araudialabeleu' ]
                                );
                            $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                            $badu = 0;
                            if ( $eremuak[ 'araudiatext' ] ) {
                                if ( ( $fitxa->getAraudiaes() !== null ) || ( $fitxa->getAraudiaeu() !== null ) ) {
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            $fitxa->getAraudiaes(),
                                            $fitxa->getAraudiaeu(),
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 1;
                                }
                            }
                            if ( $eremuak[ "araudiatable" ] ) {

                                if ( $fitxa->getAraudiak() ) {
                                    $doctextes = "<ul>";
                                    $doctexteu = "<ul>";
                                    foreach ( $fitxa->getAraudiak() as $araua ) {
                                        $doctextes = $doctextes . "<li>";
                                        $doctexteu = $doctexteu . "<li>";

                                        if ( null !== $araua->getAraudia() ) {
                                            if ( $araua->getAraudia()->getEstekaeu() ) {
                                                $doctextes = $doctextes . "<a href='" . $araua->getAraudia()->getEstekaes() . "' target='_blank'>" . $araua->getAraudia()->getArauaes() . "</a> " . $araua->getAtalaes();
                                                $doctexteu = $doctexteu . "<a href='" . $araua->getAraudia()->getEstekaeu() . "' target='_blank'>" . $araua->getAraudia()->getArauaeu() . "</a> " . $araua->getAtalaeu();
                                            } else {
                                                if ( $araua->getAraudia()->getArauaeu() ) {
                                                    $doctextes = $doctextes . $araua->getAraudia()->getArauaes() . " - " . $araua->getAtalaes();
                                                    $doctexteu = $doctexteu . $araua->getAraudia()->getArauaeu() . " - " . $araua->getAtalaeu();
                                                }
                                            }
                                        }
                                        $doctextes = $doctextes . "</li>";
                                        $doctexteu = $doctexteu . "</li>";
                                        $badu = 1;
                                    }
                                    $doctextes = $doctextes . "</ul>";
                                    $doctexteu = $doctexteu . "</ul>";

                                    if ( $badu == 1 ) {
                                        $sql = $sql . $this->addElementua(
                                                $A204AYUNTA,
                                                $idElementua,
                                                "Texto",
                                                $doctextes,
                                                $doctexteu,
                                                "PARRAFO"
                                            );
                                        $sql = $sql . $this->addElementuaBloque(
                                                $A204AYUNTA,
                                                $idBlokea,
                                                $idElementua,
                                                $idOrdenElementua
                                            );
                                        $idElementua += 1;
                                        $idOrdenElementua += 1;
                                    }
                                }

                            }
                            if ( $badu == 0 ) {
                                // Ez dagokio
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        "No corresponde",
                                        "Ez dagokio",
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 0;
                            }
                            $idBlokea += 1;
                            $idOrden += 1;
                        }
                        /****** FIN ARAUDIA *********************************************************************/

                        /****** HASI PROZEDURA *********************************************************************/
                        if ( ( $eremuak[ 'prozeduratext' ] ) || ( $eremuak[ 'prozeduratable' ] ) ) {
                            $sql = $sql . $this->addBloque(
                                    $A204AYUNTA,
                                    $idBlokea,
                                    $labelak[ 'prozeduralabeles' ],
                                    $labelak[ 'prozeduralabeleu' ]
                                );
                            $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                            $badu = 0;
                            if ( $eremuak[ 'prozeduratext' ] ) {
                                if ( ( $fitxa->getProzeduraes() !== null ) || ( $fitxa->getProzeduraeu() !== null ) ) {
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            $fitxa->getProzeduraes(),
                                            $fitxa->getProzeduraeu(),
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 1;
                                }
                            }
                            if ( $eremuak[ "prozeduratable" ] ) {

                                if ( $fitxa->getProzedurak() ) {
                                    $doctextes = "<ul>";
                                    $doctexteu = "<ul>";
                                    foreach ( $fitxa->getProzedurak() as $prozedura ) {
                                        $doctextes = $doctextes . "<li>" . $prozedura->getProzedura()->getProzeduraes() . "</li>";
                                        $doctexteu = $doctexteu . "<li>" . $prozedura->getProzedura()->getProzeduraeu() . "</li>";
                                        $badu = 1;
                                    }
                                    $doctextes = $doctextes . "</ul>";
                                    $doctexteu = $doctexteu . "</ul>";

                                    if ( $badu == 1 ) {
                                        $sql = $sql . $this->addElementua(
                                                $A204AYUNTA,
                                                $idElementua,
                                                "Texto",
                                                $doctextes,
                                                $doctexteu,
                                                "PARRAFO"
                                            );
                                        $sql = $sql . $this->addElementuaBloque(
                                                $A204AYUNTA,
                                                $idBlokea,
                                                $idElementua,
                                                $idOrdenElementua
                                            );
                                        $idElementua += 1;
                                        $idOrdenElementua += 1;
                                    }
                                }
                            }
                            if ( $badu == 0 ) {
                                // Ez dagokio
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        "No corresponde",
                                        "Ez dagokio",
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 0;
                            }
                            $idBlokea += 1;
                            $idOrden += 1;
                        }
                        /****** FIN PROZEDURA *********************************************************************/

                        /****** HASI NORK EBATZI ******************************************************************/
                        if ( ( $eremuak[ 'norkebatzitext' ] ) || ( $eremuak[ 'norkebatzitable' ] ) ) {
                            $sql = $sql . $this->addBloque(
                                    $A204AYUNTA,
                                    $idBlokea,
                                    $labelak[ 'norkebatzilabeles' ],
                                    $labelak[ 'norkebatzilabeleu' ]
                                );
                            $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                            $badu = 0;
                            if ( $eremuak[ "norkebatzitable" ] && $fitxa->getNorkebatzi() ) {

                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        $fitxa->getNorkebatzi()->getNorkes(),
                                        $fitxa->getNorkebatzi()->getNorkeu(),
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 1;

                            }
                            if ( $eremuak[ 'norkebatzitext' ] ) {
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        $fitxa->getNorkonartues(),
                                        $fitxa->getNorkonartueu(),
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 1;
                            }
                            if ( $badu == 0 ) {
                                // Ez dagokio
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        "No corresponde",
                                        "Ez dagokio",
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 0;
                            }

                            $idBlokea += 1;
                            $idOrden += 1;
                        }
                        /****** FIN NORK EBATZI *******************************************************************/

                        /****** HASI AZPISAILA ******************************************************************/
                        if ( ( $eremuak[ 'azpisailatable' ] ) ) {
                            $sql = $sql . $this->addBloque(
                                    $A204AYUNTA,
                                    $idBlokea,
                                    $labelak[ 'azpisailalabeles' ],
                                    $labelak[ 'azpisailalabeleu' ]
                                );
                            $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                            $badu = 0;
                            if ( $fitxa->getAzpisaila() ) {

                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        $fitxa->getAzpisaila()->getSaila()->getSailaes() . " - " . $fitxa->getAzpisaila()->getAzpisailaes(),
                                        $fitxa->getAzpisaila()->getSaila()->getSailaeu() . " - " . $fitxa->getAzpisaila()->getAzpisailaeu(),
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 1;
                            }
                            if ( $badu == 0 ) {
                                // Ez dagokio
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        "No corresponde",
                                        "Ez dagokio",
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 0;
                            }
                            $idBlokea += 1;
                            $idOrden += 1;
                        }
                        /****** FIN AZPISAILA *******************************************************************/

                        /****** HASI OHARRAK ******************************************************************/
                        if ( ( $eremuak[ 'oharraktext' ] ) ) {
                            $sql = $sql . $this->addBloque(
                                    $A204AYUNTA,
                                    $idBlokea,
                                    $labelak[ 'oharraklabeles' ],
                                    $labelak[ 'oharraklabeleu' ]
                                );
                            $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                            if ( ( strlen( $fitxa->getOharrakes() ) > 0 ) && ( strlen(
                                        $fitxa->getOharrakeu()
                                    ) > 0 )
                            ) {
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        $fitxa->getOharrakes(),
                                        $fitxa->getOharrakeu(),
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                            }
                            $idBlokea += 1;
                            $idOrden += 1;
                        }
                        /****** FIN OHARRAK *******************************************************************/

                        /****** HASI BESTEAK1 *********************************************************************/
                        if ( ( $eremuak[ 'besteak1text' ] ) || ( $eremuak[ 'besteak1table' ] ) ) {
                            $sql = $sql . $this->addBloque(
                                    $A204AYUNTA,
                                    $idBlokea,
                                    $labelak[ 'besteak1labeles' ],
                                    $labelak[ 'besteak1labeleu' ]
                                );
                            $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                            $badu = 0;
                            if ( $eremuak[ 'besteak1text' ] ) {
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        $fitxa->getBesteak1es(),
                                        $fitxa->getBesteak1eu(),
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 1;
                            }
                            if ( $eremuak[ "besteak1table" ] ) {

                                if ( $fitxa->getBesteak1ak() ) {
                                    $doctextes = "<ul>";
                                    $doctexteu = "<ul>";
                                    foreach ( $fitxa->getBesteak1ak() as $bes ) {
                                        $doctextes = $doctextes . "<li>";
                                        $doctexteu = $doctexteu . "<li>";
                                        if ( $bes->getEstekaes() ) {
                                            $doctextes = $doctextes . "<a href='" . $bes->getEstekaes() . "'>" . $bes->getIzenburuaes() . "</a>";
                                        } else {
                                            $doctextes = $doctextes . $bes->getIzenburuaes();
                                        }
                                        if ( $bes->getEstekaeu() ) {
                                            $doctexteu = $doctexteu . "<a href='" . $bes->getEstekaeu() . "'>" . $bes->getIzenburuaeu() . "</a>";
                                        } else {
                                            $doctexteu = $doctexteu . $bes->getIzenburuaeu();
                                        }
                                        $doctextes = $doctextes . "</li>";
                                        $doctexteu = $doctexteu . "</li>";
                                        $badu = 1;
                                    }
                                    $doctextes = $doctextes . "</ul>";
                                    $doctexteu = $doctexteu . "</ul>";

                                    if ( $badu == 1 ) {
                                        $sql = $sql . $this->addElementua(
                                                $A204AYUNTA,
                                                $idElementua,
                                                "Texto",
                                                $doctextes,
                                                $doctexteu,
                                                "PARRAFO"
                                            );
                                        $sql = $sql . $this->addElementuaBloque(
                                                $A204AYUNTA,
                                                $idBlokea,
                                                $idElementua,
                                                $idOrdenElementua
                                            );
                                        $idElementua += 1;
                                        $idOrdenElementua += 1;
                                    }
                                }
                            }
                            if ( $badu == 0 ) {
                                // Ez dagokio
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        "No corresponde",
                                        "Ez dagokio",
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 0;
                            }
                            $idBlokea += 1;
                            $idOrden += 1;
                        }
                        /****** FIN BESTEAK1 *********************************************************************/

                        /****** HASI BESTEAK2 *********************************************************************/
                        if ( ( $eremuak[ 'besteak2text' ] ) || ( $eremuak[ 'besteak2table' ] ) ) {
                            $sql = $sql . $this->addBloque(
                                    $A204AYUNTA,
                                    $idBlokea,
                                    $labelak[ 'besteak2labeles' ],
                                    $labelak[ 'besteak2labeleu' ]
                                );
                            $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                            $badu = 0;
                            if ( $eremuak[ 'besteak2text' ] ) {
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        $fitxa->getBesteak2es(),
                                        $fitxa->getBesteak2eu(),
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 1;
                            }
                            if ( $eremuak[ "besteak2table" ] ) {

                                if ( $fitxa->getBesteak2ak() ) {
                                    $doctextes = "<ul>";
                                    $doctexteu = "<ul>";
                                    foreach ( $fitxa->getBesteak2ak() as $bes ) {
                                        $doctextes = $doctextes . "<li>";
                                        $doctexteu = $doctexteu . "<li>";
                                        if ( $bes->getEstekaes() ) {
                                            $doctextes = $doctextes . "<a href='" . $bes->getEstekaes() . "'>" . $bes->getIzenburuaes() . "</a>";
                                        } else {
                                            $doctextes = $doctextes . $bes->getIzenburuaes();
                                        }
                                        if ( $bes->getEstekaeu() ) {
                                            $doctexteu = $doctexteu . "<a href='" . $bes->getEstekaeu() . "'>" . $bes->getIzenburuaeu() . "</a>";
                                        } else {
                                            $doctexteu = $doctexteu . $bes->getIzenburuaeu();
                                        }
                                        $doctextes = $doctextes . "</li>";
                                        $doctexteu = $doctexteu . "</li>";
                                        $badu = 1;
                                    }
                                    $doctextes = $doctextes . "</ul>";
                                    $doctexteu = $doctexteu . "</ul>";

                                    if ( $badu == 1 ) {
                                        $sql = $sql . $this->addElementua(
                                                $A204AYUNTA,
                                                $idElementua,
                                                "Texto",
                                                $doctextes,
                                                $doctexteu,
                                                "PARRAFO"
                                            );
                                        $sql = $sql . $this->addElementuaBloque(
                                                $A204AYUNTA,
                                                $idBlokea,
                                                $idElementua,
                                                $idOrdenElementua
                                            );
                                        $idElementua += 1;
                                        $idOrdenElementua += 1;
                                    }
                                }

                            }
                            if ( $badu == 0 ) {
                                // Ez dagokio
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        "No corresponde",
                                        "Ez dagokio",
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 0;
                            }
                            $idBlokea += 1;
                            $idOrden += 1;
                        }
                        /****** FIN BESTEAK2 *********************************************************************/

                        /****** HASI BESTEAK3 *********************************************************************/
                        if ( ( $eremuak[ 'besteak3text' ] ) || ( $eremuak[ 'besteak3table' ] ) ) {
                            $sql = $sql . $this->addBloque(
                                    $A204AYUNTA,
                                    $idBlokea,
                                    $labelak[ 'besteak3labeles' ],
                                    $labelak[ 'besteak3labeleu' ]
                                );
                            $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                            $badu = 0;
                            if ( $eremuak[ 'besteak3text' ] ) {
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        $fitxa->getBesteak3es(),
                                        $fitxa->getBesteak3eu(),
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 1;
                            }
                            if ( $eremuak[ "besteak3table" ] ) {

                                if ( $fitxa->getBesteak3ak() ) {
                                    $doctextes = "<ul>";
                                    $doctexteu = "<ul>";
                                    foreach ( $fitxa->getBesteak3ak() as $bes ) {
                                        $doctextes = $doctextes . "<li>";
                                        $doctexteu = $doctexteu . "<li>";
                                        if ( $bes->getEstekaes() ) {
                                            $doctextes = $doctextes . "<a href='" . $bes->getEstekaes() . "'>" . $bes->getIzenburuaes() . "</a>";
                                        } else {
                                            $doctextes = $doctextes . $bes->getIzenburuaes();
                                        }
                                        if ( $bes->getEstekaeu() ) {
                                            $doctexteu = $doctexteu . "<a href='" . $bes->getEstekaeu() . "'>" . $bes->getIzenburuaeu() . "</a>";
                                        } else {
                                            $doctexteu = $doctexteu . $bes->getIzenburuaeu();
                                        }
                                        $doctextes = $doctextes . "</li>";
                                        $doctexteu = $doctexteu . "</li>";
                                        $badu = 1;
                                    }
                                    $doctextes = $doctextes . "</ul>";
                                    $doctexteu = $doctexteu . "</ul>";

                                    if ( $badu == 1 ) {
                                        $sql = $sql . $this->addElementua(
                                                $A204AYUNTA,
                                                $idElementua,
                                                "Texto",
                                                $doctextes,
                                                $doctexteu,
                                                "PARRAFO"
                                            );
                                        $sql = $sql . $this->addElementuaBloque(
                                                $A204AYUNTA,
                                                $idBlokea,
                                                $idElementua,
                                                $idOrdenElementua
                                            );
                                        $idElementua += 1;
                                        $idOrdenElementua += 1;
                                    }
                                }
                            }
                            if ( $badu == 0 ) {
                                // Ez dagokio
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        "No corresponde",
                                        "Ez dagokio",
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                                $badu = 0;
                            }
                            $idBlokea += 1;
                            $idOrden += 1;
                        }
                        /****** FIN BESTEAK3 *********************************************************************/

                        /****** HASI DATUENBABESA *********************************************************************/
                        if ( ( $eremuak[ 'datuenbabesatext' ] ) || ( $eremuak[ 'datuenbabesatable' ] ) ) {
                            $sql = $sql . $this->addBloque(
                                    $A204AYUNTA,
                                    $idBlokea,
                                    $labelak[ 'datuenbabesalabeles' ],
                                    $labelak[ 'datuenbabesalabeleu' ]
                                );
                            $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                            $badu = 0;
                            if ( $eremuak[ "datuenbabesatable" ] && $fitxa->getDatuenbabesa() ) {

                                $textes = $fitxa->getUdala()->getLopdes();

                                $textes = str_replace(
                                    '$$$fitxa.datuenbabesa.izenaes$$$',
                                    $fitxa->getDatuenbabesa()->getIzenaes(),
                                    $textes
                                );
                                $textes = str_replace(
                                    '$$$fitxa.datuenbabesa.kodea$$$',
                                    $fitxa->getDatuenbabesa()->getKodea(),
                                    $textes
                                );
                                $textes = str_replace(
                                    '$$$fitxa.datuenbabesa.xedeaes$$$',
                                    $fitxa->getDatuenbabesa()->getXedeaes(),
                                    $textes
                                );
                                $textes = str_replace(
                                    '$$$fitxa.datuenbabesa.lagapenakes$$$',
                                    $fitxa->getDatuenbabesa()->getLagapenakes(),
                                    $textes
                                );

                                $texteu = $fitxa->getUdala()->getLopdeu();
                                $texteu = str_replace(
                                    '$$$fitxa.datuenbabesa.izenaeu$$$',
                                    $fitxa->getDatuenbabesa()->getIzenaeu(),
                                    $texteu
                                );
                                $texteu = str_replace(
                                    '$$$fitxa.datuenbabesa.kodea$$$',
                                    $fitxa->getDatuenbabesa()->getKodea(),
                                    $texteu
                                );
                                $texteu = str_replace(
                                    '$$$fitxa.datuenbabesa.xedeaeu$$$',
                                    $fitxa->getDatuenbabesa()->getXedeaeu(),
                                    $texteu
                                );
                                $texteu = str_replace(
                                    '$$$fitxa.datuenbabesa.lagapenakeu$$$',
                                    $fitxa->getDatuenbabesa()->getLagapenakeu(),
                                    $texteu
                                );
                                $badu = 1;
                            }


                            if ( $eremuak[ 'datuenbabesatext' ] ) {
                                $textes = $textes . $fitxa->getDatuenbabesaes();
                                $texteu = $texteu . $fitxa->getDatuenbabesaeu();
                                $badu = 1;
                            }

                            if ( $badu == 1 ) {
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        $textes,
                                        $texteu,
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                            } else {
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        "No corresponde",
                                        "Ez dagokio",
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idElementua += 1;
                                $idOrdenElementua += 1;
                            }
                            $idBlokea += 1;
                            $idOrden += 1;
                        }
                        /****** FIN DATUENBABESA *********************************************************************/

                    }

                    // Esteka sortu Home-an

                    $sql = $sql . $this->addElementua(
                            $A204AYUNTA,
                            $idElementua,
                            'Link-' . $fitxa->getEspedientekodea(),
                            $fitxa->getDeskribapenaes(),
                            $fitxa->getDeskribapenaeu(),
                            $fitxa->getParametroa(),
                            $sortutakoFitxak[ $fitxa->getEspedientekodea() ]
                        );
                    $sql = $sql . $this->addElementuaBloque(
                            $A204AYUNTA,
                            $mapa[ $familia->getId() ],
                            $idElementua,
                            $idOrdenElementua
                        );
                    $idElementua += 1;


                    $idPagina += 1;
                    if ( !$debug ) {
                        $progress->advance();
                    }


                }
                /**************************************************************************************************/
                /**** FIN              ****************************************************************************/
                /**** addFitxa()       ****************************************************************************/
                /**************************************************************************************************/
            }

            foreach ( $familia->getChildren() as $c ) {
                if ( $debug ) {
                    echo "     \n";
                    echo "   |" . $c . "|\n";
                    echo "   ---------------------------------------------\n";
                }
                foreach ( $c->getFitxafamilia() as $fitx ) {
                    if ( $debug ) {
                        echo "      |__" . $fitx->getFitxa()->getDeskribapenaeu() . "\n";
                    }

                    if ( !in_array( $c->getId(), $sortutakoAzpifamiliak ) ) {
                        /* Azpifamilia gehitu parrafo elementu gisa bloquean */
                        $sql = $sql . $this->addElementua(
                                $A204AYUNTA,
                                $idElementua,
                                "Texto",
                                "<br/><span class='bold' style='font-style:normal !important'>" . $c->getFamiliaes() . "</span>",
                                "<br/><span class='bold' style='font-style:normal !important'>" . $c->getFamiliaeu() . "</span>",
                                "PARRAFO"
                            );
                        $sql = $sql . $this->addElementuaBloque(
                                $A204AYUNTA,
                                $mapa[ $familia->getId() ],
                                $idElementua,
                                $idOrdenElementua
                            );

                        array_push( $sortutakoAzpifamiliak, $c->getId() );

                        $idOrden += 1;
                        $idOrdenElementua += 1;
                        $idElementua += 1;
                    }

                    $fitxa = $fitx->getFitxa();
                    $this->unekoFitxaKodea = $fitxa->getEspedientekodea();

                    /**************************************************************************************************/
                    /**** Fitxak-a sortu   ****************************************************************************/
                    /**** addFitxa()       ****************************************************************************/
                    /**************************************************************************************************/
                    if ( 1 == 1 ) { // Folding egiteko addFitxa bezala refaktorizatu beharko zen

                        if ( !in_array(
                            $fitxa->getEspedientekodea(),
                            $sortutakoFitxak
                        )
                        ) { // Begiratu ea aldez aurretik sortu dugun...
                            $kostuZerrenda = [];
                            foreach ( $fitxa->getKostuak() as $kostu ) {
                                if ( ( strlen( $this->zzoo_aplikazioaren_API_url ) > 0 ) && ( $kostu->getKostua() ) ) {
                                    $client = new GuzzleHttp\Client();
                                    $proba = $client->request(
                                        'GET',
                                        $this->zzoo_aplikazioaren_API_url . '/zerga/' . $kostu->getKostua() . '?format=json'
                                    );
                                    $fitxaKostua = (string)$proba->getBody();
                                    $array = json_decode( $fitxaKostua, true );
                                    $kostuZerrenda[] = $array;
                                }
                            }

                            // Orria sortu fitxarentzat
                            $sql = $sql . $this->addOrria(
                                    $A204AYUNTA,
                                    $idPagina,
                                    $fitxa->getEspedientekodea(),
                                    $fitxa->getDeskribapenaes(),
                                    $fitxa->getDeskribapenaeu(),
                                    1,
                                    $fitxa->getParametroa()
                                );

                            $sortutakoFitxak[ $fitxa->getEspedientekodea() ] = $idPagina;

                            /* FITXAREN ERAMUAK GEHITU */
                            /****** HASI HELBURUA *********************************************************************/
                            if ( $eremuak[ 'helburuatext' ] ) {
                                $sql = $sql . $this->addBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $labelak[ 'helburualabeles' ],
                                        $labelak[ 'helburualabeleu' ]
                                    );
                                $sql = $sql . $this->addOrriaBloque(
                                        $A204AYUNTA,
                                        $idPagina,
                                        $idBlokea,
                                        $idOrden
                                    );
                                $sql = $sql . $this->addElementua(
                                        $A204AYUNTA,
                                        $idElementua,
                                        "Texto",
                                        $fitxa->getHelburuaes() . "<span id='kodea' style='display:none;'>" . $fitxa->getEspedientekodea() . "</span>",
                                        $fitxa->getHelburuaeu() . "<span id='kodea' style='display:none;'>" . $fitxa->getEspedientekodea() . "</span>",
                                        "PARRAFO"
                                    );
                                $sql = $sql . $this->addElementuaBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $idElementua,
                                        $idOrdenElementua
                                    );
                                $idBlokea += 1;
                                $idOrden += 1;
                                $idOrdenElementua += 1;
                                $idElementua += 1;
                            }
                            /****** FIN HELBURUA *********************************************************************/

                            /****** HASI NORK ESKATU *********************************************************************/
                            if ( ( $eremuak[ 'norkeskatutext' ] ) || ( $eremuak[ 'norkeskatutable' ] ) ) {
                                $sql = $sql . $this->addBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $labelak[ 'norkeskatulabeles' ],
                                        $labelak[ 'norkeskatulabeleu' ]
                                    );
                                $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                                $badu = 0;
                                if ( $eremuak[ 'norkeskatutext' ] ) {

                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            $fitxa->getNorkes(),
                                            $fitxa->getNorkeu(),
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $badu = 1;
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                }
                                if ( $eremuak[ "norkeskatutable" ] ) {
                                    if ( $fitxa->getNorkeskatuak() ) {
                                        foreach ( $fitxa->getNorkeskatuak() as $nork ) {
                                            $sql = $sql . $this->addElementua(
                                                    $A204AYUNTA,
                                                    $idElementua,
                                                    "Texto",
                                                    $nork->getNorkes(),
                                                    $nork->getNorkeu(),
                                                    "PARRAFO"
                                                );
                                            $sql = $sql . $this->addElementuaBloque(
                                                    $A204AYUNTA,
                                                    $idBlokea,
                                                    $idElementua,
                                                    $idOrdenElementua
                                                );
                                            $idElementua += 1;
                                            $idOrdenElementua += 1;
                                            $badu = 1;
                                        }
                                    }
                                }
                                if ( $badu == 0 ) {
                                    // Ez dagokio
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            "No corresponde",
                                            "Ez dagokio",
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 0;
                                }
                                $idBlokea += 1;
                                $idOrden += 1;
                            }
                            /****** FIN NORK ESKATU *********************************************************************/

                            /****** HASI DOKUMENTAZIOA *********************************************************************/
                            if ( ( $eremuak[ 'dokumentazioatext' ] ) || ( $eremuak[ 'dokumentazioatable' ] ) ) {
                                $sql = $sql . $this->addBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $labelak[ 'dokumentazioalabeles' ],
                                        $labelak[ 'dokumentazioalabeleu' ]
                                    );
                                $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                                $badu = 0;
                                if ( $eremuak[ "dokumentazioatable" ] ) {
                                    if ( $fitxa->getDokumentazioak() ) {
                                        $doctextes = "<ul>";
                                        $doctexteu = "<ul>";
                                        foreach ( $fitxa->getDokumentazioak() as $doc ) {
                                            $doctextes = $doctextes . "<li>";
                                            $doctexteu = $doctexteu . "<li>";

                                            if ( $doc->getEstekaeu() ) {
                                                $doctextes = $doctextes . "<a href='" . $doc->getEstekaes() . "' target='_blank'>" . $doc->getKodea() . " " . $doc->getDeskribapenaes() . "</a>";
                                                $doctexteu = $doctexteu . "<a href='" . $doc->getEstekaeu() . "' target='_blank'>" . $doc->getKodea() . " " . $doc->getDeskribapenaeu() . "</a>";
                                            } else {
                                                $doctextes = $doctextes . $doc->getKodea() . " " . $doc->getDeskribapenaes();
                                                $doctexteu = $doctextes . $doc->getKodea() . " " . $doc->getDeskribapenaeu();
                                            }
                                            $doctextes = $doctextes . "</li>";
                                            $doctexteu = $doctexteu . "</li>";
                                            $badu = 1;
                                        }
                                        $doctextes = $doctextes . "</ul>";
                                        $doctexteu = $doctexteu . "</ul>";

                                        if ( $badu == 1 ) {
                                            $sql = $sql . $this->addElementua(
                                                    $A204AYUNTA,
                                                    $idElementua,
                                                    "Texto",
                                                    $doctextes,
                                                    $doctexteu,
                                                    "PARRAFO"
                                                );
                                            $sql = $sql . $this->addElementuaBloque(
                                                    $A204AYUNTA,
                                                    $idBlokea,
                                                    $idElementua,
                                                    $idOrdenElementua
                                                );
                                            $idElementua += 1;
                                            $idOrdenElementua += 1;
                                        }
                                    }
                                }
                                if ( $eremuak[ 'dokumentazioatext' ] ) {
                                    if ( ( $fitxa->getDokumentazioaes() !== null ) || ( $fitxa->getDokumentazioaeu() !== null )
                                    ) {
                                        $sql = $sql . $this->addElementua(
                                                $A204AYUNTA,
                                                $idElementua,
                                                "Texto",
                                                $fitxa->getDokumentazioaes(),
                                                $fitxa->getDokumentazioaeu(),
                                                "PARRAFO"
                                            );
                                        $sql = $sql . $this->addElementuaBloque(
                                                $A204AYUNTA,
                                                $idBlokea,
                                                $idElementua,
                                                $idOrdenElementua
                                            );
                                        $idElementua += 1;
                                        $idOrdenElementua += 1;
                                        $badu = 1;
                                    }
                                }
                                if ( $badu == 0 ) {
                                    // Ez dagokio
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            "No corresponde",
                                            "Ez dagokio",
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 0;
                                }
                                $idBlokea += 1;
                                $idOrden += 1;
                            }
                            /****** FIN DOKUMENTAZIOA *********************************************************************/

                            /****** HASI DOKUMENTAZIO LAGUNGARRIA *********************************************************************/
                            if ( ( $eremuak[ 'doklaguntext' ] ) || ( $eremuak[ 'doklaguntable' ] ) ) {
                                $sql = $sql . $this->addBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $labelak[ 'dokumentazioalabeles' ],
                                        $labelak[ 'dokumentazioalabeleu' ]
                                    );
                                $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                                $badu = 0;
                                if ( $eremuak[ 'doklaguntext' ] ) {
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            $fitxa->getDoklagunes(),
                                            $fitxa->getDoklaguneu(),
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 1;
                                }

                                if ( $eremuak[ "doklaguntable" ] ) {
                                    if ( $fitxa->doklagunak() ) {
                                        $doctextes = "<ul>";
                                        $doctexteu = "<ul>";
                                        foreach ( $fitxa->doklagunak() as $doc ) {
                                            $doctextes = $doctextes . "<li>";
                                            $doctexteu = $doctexteu . "<li>";

                                            if ( $doc->getEstekaeu() ) {
                                                $doctextes = "<a href='" . $doc->getEstekaes() . "' target='_blank'>" . $doc->getKodea() . " " . $doc->getDeskribapenaes() . "</a>";
                                                $doctexteu = "<a href='" . $doc->getEstekaeu() . "' target='_blank'>" . $doc->getKodea() . " " . $doc->getDeskribapenaeu() . "</a>";
                                            } else {
                                                $doctextes = $doc->getKodea() . " " . $doc->getDeskribapenaes();
                                                $doctexteu = $doc->getKodea() . " " . $doc->getDeskribapenaeu();
                                            }
                                            $doctextes = $doctextes . "</li>";
                                            $doctexteu = $doctexteu . "</li>";
                                            $badu = 1;
                                        }

                                        if ( $badu == 1 ) {
                                            $sql = $sql . $this->addElementua(
                                                    $A204AYUNTA,
                                                    $idElementua,
                                                    "Texto",
                                                    $doctextes,
                                                    $doctexteu,
                                                    "PARRAFO"
                                                );
                                            $sql = $sql . $this->addElementuaBloque(
                                                    $A204AYUNTA,
                                                    $idBlokea,
                                                    $idElementua,
                                                    $idOrdenElementua
                                                );
                                            $idElementua += 1;
                                            $idOrdenElementua += 1;
                                        }
                                    }
                                }
                                if ( $badu == 0 ) {
                                    // Ez dagokio
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            "No corresponde",
                                            "Ez dagokio",
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 0;
                                }
                                $idBlokea += 1;
                                $idOrden += 1;
                            }
                            /****** FIN DOKUMENTAZIO LAGUNGARRIA *********************************************************************/

                            /****** HASI KANALA *********************************************************************/
                            if ( ( $eremuak[ 'kanalatext' ] ) || ( $eremuak[ 'kanalatable' ] ) ) {
                                $sql = $sql . $this->addBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $labelak[ 'kanalalabeles' ],
                                        $labelak[ 'kanalalabeleu' ]
                                    );
                                $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                                $badu = 0;
                                if ( $eremuak[ "kanalatable" ] ) {

                                    foreach ( $kanalmotak as $k ) {
                                        $aurkitua = 0;
                                        $textes = "<ul>";
                                        $texteu = "<ul>";

                                        /** @var Kanala $kanala */
                                        foreach ( $fitxa->getKanalak() as $kanala ) {
                                            if ( ( $kanala->getKanalmota() == $k ) && ( $kanala->getErakutsi() == 1 )
                                            ) {
                                                if ( $aurkitua == 0 ) {
                                                    if ( $k->getIkonoa() ) {
                                                        $textes = $textes . "<i class='fa " . $k->getIkonoa() . " aria-hidden='true'></i>";
                                                        $texteu = $texteu . "<i class='fa " . $k->getIkonoa() . " aria-hidden='true'></i>";
                                                    }
                                                    $textes = $textes . "<b>" . $k->getMotaes() . ":</b>";
                                                    $texteu = $texteu . "<b>" . $k->getMotaeu() . ":</b>";
                                                    $aurkitua = 1;
                                                }
                                                if ( $kanala->getTelematikoa() ) {
                                                    if ( $fitxa->getZerbitzua() ) {
                                                        if (( strlen($fitxa->getExpedientes()) > 0 )  && ( str_starts_with($fitxa->getParametroa(), "08") )) {
                                                            $textes = $textes . "<li><a href='" . $fitxa->getZerbitzua()->getErroaes() . $fitxa->getUdala()->getKodea() . $fitxa->getParametroa() . $fitxa->getExpedientes() . "' target='_blank'>" . $kanala->getIzenaes() . "</a></li>";
                                                            $texteu = $texteu . "<li><a href='" . $fitxa->getZerbitzua()->getErroaeu() . $fitxa->getUdala()->getKodea() . $fitxa->getParametroa() . $fitxa->getExpedientes() . "' target='_blank'>" . $kanala->getIzenaeu() . "</a></li>";
                                                        } else {
                                                            $textes = $textes . "<li><a href='" . $fitxa->getZerbitzua()->getErroaes() . $fitxa->getUdala()->getKodea() . $fitxa->getParametroa() . "' target='_blank'>" . $kanala->getIzenaes() . "</a></li>";
                                                            $texteu = $texteu . "<li><a href='" . $fitxa->getZerbitzua()->getErroaeu() . $fitxa->getUdala()->getKodea() . $fitxa->getParametroa() . "' target='_blank'>" . $kanala->getIzenaeu() . "</a></li>";
                                                        }
                                                    }
                                                } else {
                                                    if ( $k->getEsteka() ) {
                                                        $textes = $textes . "<li>";
                                                        $texteu = $texteu . "<li>";
                                                        if ( $kanala->getIzenaes() ) {
                                                            if ( ( preg_match(
                                                                    '/@/',
                                                                    $kanala->getEstekaes()
                                                                ) ) && ( !preg_match(
                                                                    '/maps/',
                                                                    $kanala->getEstekaes()
                                                                ) )
                                                            ) {
                                                                $textes = $textes . "<a href='mailto:" . $kanala->getEstekaes() . "'>" . $kanala->getIzenaes() . "</a><br />";
                                                                $texteu = $texteu . "<a href='mailto:" . $kanala->getEstekaeu() . "'>" . $kanala->getIzenaeu() . "</a><br />";
                                                            } else {
                                                                $textes = $textes . "<a href='" . $kanala->getEstekaes() . "' target='_blank'>" . $kanala->getIzenaes() . "</a><br />";
                                                                $texteu = $texteu . "<a href='" . $kanala->getEstekaeu() . "' target='_blank'>" . $kanala->getIzenaeu() . "</a><br />";
                                                            }
                                                        }
                                                        if ( $kanala->getEraikina() ) {
                                                            $textes = $textes . $kanala->getEraikina()->getIzena() . "<br />";
                                                            $texteu = $texteu . $kanala->getEraikina()->getIzena() . "<br />";
                                                        }
                                                        if ( $kanala->getKalea() ) {
                                                            $textes = $textes . $kanala->getKalea() . " ";
                                                            $texteu = $texteu . $kanala->getKalea() . " ";
                                                        }
                                                        if ( $kanala->getKalezbkia() ) {
                                                            $textes = $textes . $kanala->getKalezbkia() . " ";
                                                            $texteu = $texteu . $kanala->getKalezbkia() . " ";
                                                        }
                                                        if ( $kanala->getPostakodea() ) {
                                                            $textes = $textes . $kanala->getPostakodea() . " ";
                                                            $texteu = $texteu . $kanala->getPostakodea() . " ";
                                                        }
                                                        if ( $kanala->getUdala() ) {
                                                            $textes = $textes . $kanala->getUdala()->getIzenaes() . "<br/>";
                                                            $texteu = $texteu . $kanala->getUdala()->getIzenaeu() . "<br/>";
                                                        }
                                                        if ( $kanala->getOrdutegia() ) {
                                                            $textes = $textes . $kanala->getOrdutegia() . "<br/>";
                                                            $texteu = $texteu . $kanala->getOrdutegia() . "<br/>";
                                                        }
                                                        if ( $kanala->getTelefonoa() ) {
                                                            $textes = $textes . $kanala->getTelefonoa() . "<br/>";
                                                            $texteu = $texteu . $kanala->getTelefonoa() . "<br/>";
                                                        }
                                                        if ( $kanala->getFax() ) {
                                                            $textes = $textes . $kanala->getFax() . "<br/>";
                                                            $texteu = $texteu . $kanala->getFax() . "<br/>";
                                                        }
                                                        $textes = $textes . "</li>";
                                                        $texteu = $texteu . "</li>";
                                                    } else { // if ($k->getEsteka())
                                                        $textes = $textes . "<li>";
                                                        $texteu = $texteu . "<li>";
                                                        if ( $kanala->getIzenaes() ) {
                                                            $textes = $textes . $kanala->getIzenaes() . "<br/>";
                                                            $texteu = $texteu . $kanala->getIzenaes() . "<br/>";
                                                        }
                                                        if ( $kanala->getKalea() ) {
                                                            $textes = $textes . $kanala->getKalea() . " ";
                                                            $texteu = $texteu . $kanala->getKalea() . " ";
                                                        }
                                                        if ( $kanala->getKalezbkia() ) {
                                                            $textes = $textes . $kanala->getKalezbkia() . " ";
                                                            $texteu = $texteu . $kanala->getKalezbkia() . " ";
                                                        }
                                                        if ( $kanala->getPostakodea() ) {
                                                            $textes = $textes . $kanala->getPostakodea() . " ";
                                                            $texteu = $texteu . $kanala->getPostakodea() . " ";
                                                        }
                                                        if ( $kanala->getUdala() ) {
                                                            $textes = $textes . $kanala->getUdala()->getIzenaes() . "<br/>";
                                                            $texteu = $texteu . $kanala->getUdala()->getIzenaeu() . "<br/>";
                                                        }
                                                        if ( $kanala->getOrdutegia() ) {
                                                            $textes = $textes . $kanala->getOrdutegia() . "<br/>";
                                                            $texteu = $texteu . $kanala->getOrdutegia() . "<br/>";
                                                        }
                                                        if ( $kanala->getTelefonoa() ) {
                                                            $textes = $textes . $kanala->getTelefonoa() . "<br/>";
                                                            $texteu = $texteu . $kanala->getTelefonoa() . "<br/>";
                                                        }
                                                        if ( $kanala->getFax() ) {
                                                            $textes = $textes . $kanala->getFax() . "<br/>";
                                                            $texteu = $texteu . $kanala->getFax() . "<br/>";
                                                        }
                                                        $textes = $textes . "</li>";
                                                        $texteu = $texteu . "</li>";
                                                    }
                                                }
                                                $badu = 1;
                                            }
                                        }
                                        $textes = $textes . "</ul>";
                                        $texteu = $texteu . "</ul>";
                                        if ( $badu == 1 ) {
                                            $sql = $sql . $this->addElementua(
                                                    $A204AYUNTA,
                                                    $idElementua,
                                                    "Texto",
                                                    $textes,
                                                    $texteu,
                                                    "PARRAFO"
                                                );
                                            $sql = $sql . $this->addElementuaBloque(
                                                    $A204AYUNTA,
                                                    $idBlokea,
                                                    $idElementua,
                                                    $idOrdenElementua
                                                );
                                            $idElementua += 1;
                                            $idOrdenElementua += 1;
                                        }
                                    }
                                }
                                if ( $eremuak[ 'kanalatext' ] ) {
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            $fitxa->getKanalaes(),
                                            $fitxa->getKanalaeu(),
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 1;
                                }
                                if ( $badu == 0 ) {
                                    // Ez dagokio
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            "No corresponde",
                                            "Ez dagokio",
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 0;
                                }
                                $idBlokea += 1;
                                $idOrden += 1;
                            }
                            /****** FIN DOKUMENTAZIOA *********************************************************************/

                            /****** HASI KOSTUA *********************************************************************/
                            if ( ( $eremuak[ 'kostuatext' ] ) || ( $eremuak[ 'kostuatable' ] ) ) {
                                $sql = $sql . $this->addBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $labelak[ 'kostualabeles' ],
                                        $labelak[ 'kostualabeleu' ]
                                    );
                                $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                                $kont = 0;
                                $textes = "";
                                $texteu = "";
                                $badu = 0;

                                if ( $eremuak[ "kostuatable" ] ) {
                                    if ( $fitxa->getUdala()->getZergaor() ) {
                                        foreach ( $kostuZerrenda as $kostutaula ) {
                                            if ( $kostutaula !== null ) {
                                                $textes = $textes . "<table  class='table table-bordered table-condensed table-hover'><tr><th colspan='2' class='text-center'>" . $kostutaula[ "kodea_prod" ] . " - " . $kostutaula[ "izenburuaes_prod" ] . "</th></tr>";
                                                $texteu = $texteu . "<table  class='table table-bordered table-condensed table-hover'><tr><th colspan='2' class='text-center'>" . $kostutaula[ "kodea_prod" ] . " - " . $kostutaula[ "izenburuaeu_prod" ] . "</th></tr>";

                                                foreach ( $kostutaula[ "parrafoak" ] as $parrafo ) {
                                                    if ( array_key_exists( "testuaes_prod", $parrafo ) ) {
                                                        $textes = $textes . "<tr><td colspan='2'>" . $parrafo[ "testuaes_prod" ] . "</td></tr>";
                                                        $texteu = $texteu . "<tr><td colspan='2'>" . $parrafo[ "testuaeu_prod" ] . "</td></tr>";
                                                    }
                                                }
                                                foreach ( $kostutaula[ "kontzeptuak" ] as $kontzeptu ) {
                                                    if ( array_key_exists( "kopurua_prod", $kontzeptu ) ) {
                                                        if ( array_key_exists( "unitatea_prod", $kontzeptu ) ) {
                                                            $textes = $textes . "<tr><td>" . $kontzeptu[ "kontzeptuaes_prod" ] . "</td><td NOWRAP>" . $kontzeptu[ "kopurua_prod" ] . " " . $kontzeptu[ "unitatea_prod" ] . "</td></tr>";
                                                            $texteu = $texteu . "<tr><td>" . $kontzeptu[ "kontzeptuaeu_prod" ] . "</td><td NOWRAP>" . $kontzeptu[ "kopurua_prod" ] . " " . $kontzeptu[ "unitatea_prod" ] . "</td></tr>";
                                                        } else {
                                                            $textes = $textes . "<tr><td>" . $kontzeptu[ "kontzeptuaes_prod" ] . "</td><td NOWRAP>" . $kontzeptu[ "kopurua_prod" ] . "</td></tr>";
                                                            $texteu = $texteu . "<tr><td>" . $kontzeptu[ "kontzeptuaeu_prod" ] . "</td><td NOWRAP>" . $kontzeptu[ "kopurua_prod" ] . "</td></tr>";
                                                        }
                                                    }
                                                }
                                                $textes = $textes . "</table>";
                                                $texteu = $texteu . "</table>";
                                                $kont += 1;
                                                $badu = 1;
                                            }
                                        }
                                    } else {
                                        foreach ( $fitxa->getAzpiatalak() as $azpiatal ) {
                                            $textes = $textes . "<table class='table table-bordered table-condensed table-hover'><tr><th colspan=2><a href='http://zergaordenantzak/kudeaketa.php/atala/show/id/" . $azpiatal->getId() . "' target='_blank'>" . $azpiatal->getKodea() . " - " . $azpiatal->getIzenburuaes() . "</a></th></tr>";
                                            $texteu = $texteu . "<table class='table table-bordered table-condensed table-hover'><tr><th colspan=2><a href='http://zergaordenantzak/kudeaketa.php/atala/show/id/" . $azpiatal->getId() . "' target='_blank'>" . $azpiatal->getKodea() . " - " . $azpiatal->getIzenburuaeu() . "</a></th></tr>";

                                            foreach ( $azpiatal->getParrafoak() as $parrafo ) {
                                                $textes = $textes . "<tr><td colspan='2'>" . $parrafo->getTestuaes() . "</td></tr>";
                                                $texteu = $texteu . "<tr><td colspan='2'>" . $parrafo->getTestuaeu() . "</td></tr>";
                                            }

                                            foreach ( $azpiatal->getKontzeptuak() as $kontzeptu ) {
                                                $textes = $textes . "<tr><td>" . $kontzeptu->getKontzeptuaes();
                                                if ( $kontzeptu->getBaldintza() ) {
                                                    $textes = $textes . $kontzeptu->getBaldintza()->getBaldintzaes();
                                                }
                                                $textes = $textes . "</td><td>" . $kontzeptu->getKopurua() . " " . $kontzeptu->getUnitatea() . "</td></tr>";

                                                $texteu = $texteu . "<tr><td>" . $kontzeptu->getKontzeptuaeu();
                                                if ( $kontzeptu->getBaldintza() ) {
                                                    $texteu = $texteu . $kontzeptu->getBaldintza()->getBaldintzaeu();
                                                }
                                                $texteu = $texteu . "</td><td>" . $kontzeptu->getKopurua() . " " . $kontzeptu->getUnitatea() . "</td></tr>";
                                            }
                                            $textes = $textes . "</table>";
                                            $texteu = $texteu . "</table>";
                                            $kont += 1;
                                            $badu = 1;
                                        }
                                    }

                                    if ( $badu == 1 ) {
                                        $sql = $sql . $this->addElementua(
                                                $A204AYUNTA,
                                                $idElementua,
                                                "Texto",
                                                $textes,
                                                $texteu,
                                                "PARRAFO"
                                            );
                                        $sql = $sql . $this->addElementuaBloque(
                                                $A204AYUNTA,
                                                $idBlokea,
                                                $idElementua,
                                                $idOrdenElementua
                                            );
                                        $idElementua += 1;
                                        $idOrdenElementua += 1;
                                    }
                                }
                                if ( $eremuak[ 'kostuatext' ] ) {
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            $fitxa->getKostuaes(),
                                            $fitxa->getKostuaeu(),
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 1;
                                }

                                if ( ( ( $fitxa->getKostuaes() == null ) && ( $kont == 0 ) ) || ( ( $fitxa->getKostuaeu() == null ) && ( $kont == 0 ) )
                                ) {
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            $labelak[ "doanlabeles" ],
                                            $labelak[ "doanlabeleu" ],
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 1;
                                }
                                if ( $badu == 0 ) {
                                    // Ez dagokio
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            "No corresponde",
                                            "Ez dagokio",
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 0;
                                }
                                $idBlokea += 1;
                                $idOrden += 1;
                            }
                            /****** FIN KOSTUA *********************************************************************/

                            /****** HASI EBAZPENA *********************************************************************/
                            if ( $eremuak[ 'ebazpensinpli' ] || ( $eremuak[ 'arduraaitorpena' ] ) || ( $eremuak[ 'aurreikusi' ] ) || ( $eremuak[ 'arrunta' ] ) || ( $eremuak[ 'isiltasunadmin' ] ) ) {
                                $sql = $sql . $this->addBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $labelak[ 'epealabeles' ],
                                        $labelak[ 'epealabeleu' ]
                                    );
                                $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                                $textes = "<ul>";
                                $texteu = "<ul>";

                                $badu = 0;
                                if ( $eremuak[ "aurreikusi" ] ) {
                                    if ( $fitxa->getAurreikusi() ) {
                                        $textes = $textes . "<li>";
                                        $texteu = $texteu . "<li>";
                                        $textes = $textes . $labelak[ "aurreikusilabeles" ] . ": " . $fitxa->getAurreikusi() . "\n";
                                        $texteu = $texteu . $labelak[ "aurreikusilabeleu" ] . ": " . $fitxa->getAurreikusi() . "\n";
                                        $textes = $textes . "</li>";
                                        $texteu = $texteu . "</li>";
                                        $badu = 1;
                                    }

                                }

                                if ( $eremuak[ "arrunta" ] ) {
                                    if ( $fitxa->getArrunta() ) {
                                        $textes = $textes . "<li>";
                                        $texteu = $texteu . "<li>";
                                        $textes = $textes . $labelak[ "arruntalabeles" ] . ": " . $fitxa->getArrunta() . "\n";
                                        $texteu = $texteu . $labelak[ "arruntalabeleu" ] . ": " . $fitxa->getArrunta() . "\n";
                                        $textes = $textes . "</li>";
                                        $texteu = $texteu . "</li>";
                                        $badu = 1;
                                    }
                                }

                                if ( $eremuak[ "ebazpensinpli" ] ) {
                                    if ( $fitxa->getEbazpensinpli() ) {
                                        $textes = $textes . "<li>";
                                        $texteu = $texteu . "<li>";
                                        $textes = $textes . $labelak[ "ebazpensinplilabeles" ] . ": " . $fitxa->getEbazpensinpli() . "<br/>" . "\n";
                                        $texteu = $texteu . $labelak[ "ebazpensinplilabeleu" ] . ": " . $fitxa->getEbazpensinpli() . "<br/>" . "\n";
                                        $textes = $textes . "</li>";
                                        $texteu = $texteu . "</li>";
                                        $badu = 1;
                                    }
                                }

                                if ( $eremuak[ "arduraaitorpena" ] ) {
                                    if ( $fitxa->getArduraaitorpena() ) {
                                        $textes = $textes . "<li>";
                                        $texteu = $texteu . "<li>";
                                        $textes = $textes . $labelak[ "arduraaitorpenalabeles" ] . ": Si <br/>" . "\n";
                                        $texteu = $texteu . $labelak[ "arduraaitorpenalabeleu" ] . ": Bai <br/>" . "\n";
                                        $textes = $textes . "</li>";
                                        $texteu = $texteu . "</li>";
                                        $badu = 1;
                                    }
                                }

                                if ( $eremuak[ "isiltasunadmin" ] ) {
                                    if ( $fitxa->getIsiltasunadmin() ) {
                                        $textes = $textes . "<li>";
                                        $texteu = $texteu . "<li>";
                                        $textes = $textes . $labelak[ "isiltasunadminlabeles" ] . ": " . $fitxa->getIsiltasunadmin() . "<br/>" . "\n";
                                        $texteu = $texteu . $labelak[ "isiltasunadminlabeleu" ] . ": " . $fitxa->getIsiltasunadmin() . "<br/>" . "\n";
                                        $textes = $textes . "</li>";
                                        $texteu = $texteu . "</li>";
                                        $badu = 1;
                                    }
                                }

                                $textes = $textes . "</ul>";
                                $texteu = $texteu . "</ul>";

                                if ( $badu == 1 ) {
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            $textes,
                                            $texteu,
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idOrdenElementua += 1;
                                    $idElementua += 1;
                                }
                                $idBlokea += 1;
                                $idOrden += 1;
                            }
                            /****** FIN EBAZPENA*********************************************************************/

                            /****** HASI ARAUDIA *********************************************************************/
                            if ( ( $eremuak[ 'araudiatext' ] ) || ( $eremuak[ 'araudiatable' ] ) ) {
                                $sql = $sql . $this->addBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $labelak[ 'araudialabeles' ],
                                        $labelak[ 'araudialabeleu' ]
                                    );
                                $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                                $badu = 0;
                                if ( $eremuak[ 'araudiatext' ] ) {
                                    if ( ( $fitxa->getAraudiaes() !== null ) || ( $fitxa->getAraudiaeu() !== null ) ) {
                                        $sql = $sql . $this->addElementua(
                                                $A204AYUNTA,
                                                $idElementua,
                                                "Texto",
                                                $fitxa->getAraudiaes(),
                                                $fitxa->getAraudiaeu(),
                                                "PARRAFO"
                                            );
                                        $sql = $sql . $this->addElementuaBloque(
                                                $A204AYUNTA,
                                                $idBlokea,
                                                $idElementua,
                                                $idOrdenElementua
                                            );
                                        $idElementua += 1;
                                        $idOrdenElementua += 1;
                                        $badu = 1;
                                    }
                                }
                                if ( $eremuak[ "araudiatable" ] ) {

                                    if ( $fitxa->getAraudiak() ) {
                                        $doctextes = "<ul>";
                                        $doctexteu = "<ul>";
                                        foreach ( $fitxa->getAraudiak() as $araua ) {
                                            $doctextes = $doctextes . "<li>";
                                            $doctexteu = $doctexteu . "<li>";

                                            if ( $araua->getAraudia()->getEstekaeu() ) {
                                                $doctextes = $doctextes . "<a href='" . $araua->getAraudia()->getEstekaes() . "' target='_blank'>" . $araua->getAraudia()->getArauaes() . "</a> " . $araua->getAtalaes();
                                                $doctexteu = $doctexteu . "<a href='" . $araua->getAraudia()->getEstekaeu() . "' target='_blank'>" . $araua->getAraudia()->getArauaeu() . "</a> " . $araua->getAtalaeu();
                                            } else {
                                                $doctextes = $doctextes . $araua->getAraudia()->getArauaes() . " - " . $araua->getAtalaes();
                                                $doctexteu = $doctexteu . $araua->getAraudia()->getArauaeu() . " - " . $araua->getAtalaeu();
                                            }
                                            $doctextes = $doctextes . "</li>";
                                            $doctexteu = $doctexteu . "</li>";
                                            $badu = 1;
                                        }
                                        $doctextes = $doctextes . "</ul>";
                                        $doctexteu = $doctexteu . "</ul>";

                                        if ( $badu == 1 ) {
                                            $sql = $sql . $this->addElementua(
                                                    $A204AYUNTA,
                                                    $idElementua,
                                                    "Texto",
                                                    $doctextes,
                                                    $doctexteu,
                                                    "PARRAFO"
                                                );
                                            $sql = $sql . $this->addElementuaBloque(
                                                    $A204AYUNTA,
                                                    $idBlokea,
                                                    $idElementua,
                                                    $idOrdenElementua
                                                );
                                            $idElementua += 1;
                                            $idOrdenElementua += 1;
                                        }
                                    }

                                }
                                if ( $badu == 0 ) {
                                    // Ez dagokio
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            "No corresponde",
                                            "Ez dagokio",
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 0;
                                }
                                $idBlokea += 1;
                                $idOrden += 1;
                            }
                            /****** FIN ARAUDIA *********************************************************************/

                            /****** HASI PROZEDURA *********************************************************************/
                            if ( ( $eremuak[ 'prozeduratext' ] ) || ( $eremuak[ 'prozeduratable' ] ) ) {
                                $sql = $sql . $this->addBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $labelak[ 'prozeduralabeles' ],
                                        $labelak[ 'prozeduralabeleu' ]
                                    );
                                $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                                $badu = 0;
                                if ( $eremuak[ 'prozeduratext' ] ) {
                                    if ( ( $fitxa->getProzeduraes() !== null ) || ( $fitxa->getProzeduraeu() !== null )
                                    ) {
                                        $sql = $sql . $this->addElementua(
                                                $A204AYUNTA,
                                                $idElementua,
                                                "Texto",
                                                $fitxa->getProzeduraes(),
                                                $fitxa->getProzeduraeu(),
                                                "PARRAFO"
                                            );
                                        $sql = $sql . $this->addElementuaBloque(
                                                $A204AYUNTA,
                                                $idBlokea,
                                                $idElementua,
                                                $idOrdenElementua
                                            );
                                        $idElementua += 1;
                                        $idOrdenElementua += 1;
                                        $badu = 1;
                                    }
                                }
                                if ( $eremuak[ "prozeduratable" ] ) {

                                    if ( $fitxa->getProzedurak() ) {
                                        $doctextes = "<ul>";
                                        $doctexteu = "<ul>";
                                        foreach ( $fitxa->getProzedurak() as $prozedura ) {
                                            $doctextes = $doctextes . "<li>" . $prozedura->getProzedura()->getProzeduraes() . "</li>";
                                            $doctexteu = $doctexteu . "<li>" . $prozedura->getProzedura()->getProzeduraeu() . "</li>";
                                            $badu = 1;
                                        }
                                        $doctextes = $doctextes . "</ul>";
                                        $doctexteu = $doctexteu . "</ul>";

                                        if ( $badu == 1 ) {
                                            $sql = $sql . $this->addElementua(
                                                    $A204AYUNTA,
                                                    $idElementua,
                                                    "Texto",
                                                    $doctextes,
                                                    $doctexteu,
                                                    "PARRAFO"
                                                );
                                            $sql = $sql . $this->addElementuaBloque(
                                                    $A204AYUNTA,
                                                    $idBlokea,
                                                    $idElementua,
                                                    $idOrdenElementua
                                                );
                                            $idElementua += 1;
                                            $idOrdenElementua += 1;
                                        }
                                    }
                                }
                                if ( $badu == 0 ) {
                                    // Ez dagokio
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            "No corresponde",
                                            "Ez dagokio",
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 0;
                                }
                                $idBlokea += 1;
                                $idOrden += 1;
                            }
                            /****** FIN PROZEDURA *********************************************************************/

                            /****** HASI NORK EBATZI ******************************************************************/
                            if ( ( $eremuak[ 'norkebatzitext' ] ) || ( $eremuak[ 'norkebatzitable' ] ) ) {
                                $sql = $sql . $this->addBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $labelak[ 'norkebatzilabeles' ],
                                        $labelak[ 'norkebatzilabeleu' ]
                                    );
                                $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                                $badu = 0;
                                if ( $eremuak[ "norkebatzitable" ] && $fitxa->getNorkebatzi() ) {

                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            $fitxa->getNorkebatzi()->getNorkes(),
                                            $fitxa->getNorkebatzi()->getNorkeu(),
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 1;

                                }
                                if ( $eremuak[ 'norkebatzitext' ] ) {
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            $fitxa->getNorkonartues(),
                                            $fitxa->getNorkonartueu(),
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 1;
                                }
                                if ( $badu == 0 ) {
                                    // Ez dagokio
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            "No corresponde",
                                            "Ez dagokio",
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 0;
                                }

                                $idBlokea += 1;
                                $idOrden += 1;
                            }
                            /****** FIN NORK EBATZI *******************************************************************/

                            /****** HASI AZPISAILA ******************************************************************/
                            if ( ( $eremuak[ 'azpisailatable' ] ) ) {
                                $sql = $sql . $this->addBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $labelak[ 'azpisailalabeles' ],
                                        $labelak[ 'azpisailalabeleu' ]
                                    );
                                $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                                $badu = 0;
                                if ( $fitxa->getAzpisaila() ) {

                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            $fitxa->getAzpisaila()->getSaila()->getSailaes() . " - " . $fitxa->getAzpisaila()->getAzpisailaes(),
                                            $fitxa->getAzpisaila()->getSaila()->getSailaeu() . " - " . $fitxa->getAzpisaila()->getAzpisailaeu(),
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 1;
                                }
                                if ( $badu == 0 ) {
                                    // Ez dagokio
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            "No corresponde",
                                            "Ez dagokio",
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 0;
                                }
                                $idBlokea += 1;
                                $idOrden += 1;
                            }
                            /****** FIN AZPISAILA *******************************************************************/

                            /****** HASI OHARRAK ******************************************************************/
                            if ( ( $eremuak[ 'oharraktext' ] ) ) {
                                $sql = $sql . $this->addBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $labelak[ 'oharraklabeles' ],
                                        $labelak[ 'oharraklabeleu' ]
                                    );
                                $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                                if ( ( strlen( $fitxa->getOharrakes() ) > 0 ) && ( strlen(
                                            $fitxa->getOharrakeu()
                                        ) > 0 )
                                ) {
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            $fitxa->getOharrakes(),
                                            $fitxa->getOharrakeu(),
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                }
                                $idBlokea += 1;
                                $idOrden += 1;
                            }
                            /****** FIN OHARRAK *******************************************************************/

                            /****** HASI BESTEAK1 *********************************************************************/
                            if ( ( $eremuak[ 'besteak1text' ] ) || ( $eremuak[ 'besteak1table' ] ) ) {
                                $sql = $sql . $this->addBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $labelak[ 'besteak1labeles' ],
                                        $labelak[ 'besteak1labeleu' ]
                                    );
                                $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                                $badu = 0;
                                if ( $eremuak[ 'besteak1text' ] ) {
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            $fitxa->getBesteak1es(),
                                            $fitxa->getBesteak1eu(),
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 1;
                                }
                                if ( $eremuak[ "besteak1table" ] ) {

                                    if ( $fitxa->getBesteak1ak() ) {
                                        $doctextes = "<ul>";
                                        $doctexteu = "<ul>";
                                        foreach ( $fitxa->getBesteak1ak() as $bes ) {
                                            $doctextes = $doctextes . "<li>";
                                            $doctexteu = $doctexteu . "<li>";
                                            if ( $bes->getEstekaes() ) {
                                                $doctextes = $doctextes . "<a href='" . $bes->getEstekaes() . "'>" . $bes->getIzenburuaes() . "</a>";
                                            } else {
                                                $doctextes = $doctextes . $bes->getIzenburuaes();
                                            }
                                            if ( $bes->getEstekaeu() ) {
                                                $doctexteu = $doctexteu . "<a href='" . $bes->getEstekaeu() . "'>" . $bes->getIzenburuaeu() . "</a>";
                                            } else {
                                                $doctexteu = $doctexteu . $bes->getIzenburuaeu();
                                            }
                                            $doctextes = $doctextes . "</li>";
                                            $doctexteu = $doctexteu . "</li>";
                                            $badu = 1;
                                        }
                                        $doctextes = $doctextes . "</ul>";
                                        $doctexteu = $doctexteu . "</ul>";

                                        if ( $badu == 1 ) {
                                            $sql = $sql . $this->addElementua(
                                                    $A204AYUNTA,
                                                    $idElementua,
                                                    "Texto",
                                                    $doctextes,
                                                    $doctexteu,
                                                    "PARRAFO"
                                                );
                                            $sql = $sql . $this->addElementuaBloque(
                                                    $A204AYUNTA,
                                                    $idBlokea,
                                                    $idElementua,
                                                    $idOrdenElementua
                                                );
                                            $idElementua += 1;
                                            $idOrdenElementua += 1;
                                        }
                                    }
                                }
                                if ( $badu == 0 ) {
                                    // Ez dagokio
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            "No corresponde",
                                            "Ez dagokio",
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 0;
                                }
                                $idBlokea += 1;
                                $idOrden += 1;
                            }
                            /****** FIN BESTEAK1 *********************************************************************/

                            /****** HASI BESTEAK2 *********************************************************************/
                            if ( ( $eremuak[ 'besteak2text' ] ) || ( $eremuak[ 'besteak2table' ] ) ) {
                                $sql = $sql . $this->addBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $labelak[ 'besteak2labeles' ],
                                        $labelak[ 'besteak2labeleu' ]
                                    );
                                $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                                $badu = 0;
                                if ( $eremuak[ 'besteak2text' ] ) {
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            $fitxa->getBesteak2es(),
                                            $fitxa->getBesteak2eu(),
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 1;
                                }
                                if ( $eremuak[ "besteak2table" ] ) {

                                    if ( $fitxa->getBesteak2ak() ) {
                                        $doctextes = "<ul>";
                                        $doctexteu = "<ul>";
                                        foreach ( $fitxa->getBesteak2ak() as $bes ) {
                                            $doctextes = $doctextes . "<li>";
                                            $doctexteu = $doctexteu . "<li>";
                                            if ( $bes->getEstekaes() ) {
                                                $doctextes = $doctextes . "<a href='" . $bes->getEstekaes() . "'>" . $bes->getIzenburuaes() . "</a>";
                                            } else {
                                                $doctextes = $doctextes . $bes->getIzenburuaes();
                                            }
                                            if ( $bes->getEstekaeu() ) {
                                                $doctexteu = $doctexteu . "<a href='" . $bes->getEstekaeu() . "'>" . $bes->getIzenburuaeu() . "</a>";
                                            } else {
                                                $doctexteu = $doctexteu . $bes->getIzenburuaeu();
                                            }
                                            $doctextes = $doctextes . "</li>";
                                            $doctexteu = $doctexteu . "</li>";
                                            $badu = 1;
                                        }
                                        $doctextes = $doctextes . "</ul>";
                                        $doctexteu = $doctexteu . "</ul>";

                                        if ( $badu == 1 ) {
                                            $sql = $sql . $this->addElementua(
                                                    $A204AYUNTA,
                                                    $idElementua,
                                                    "Texto",
                                                    $doctextes,
                                                    $doctexteu,
                                                    "PARRAFO"
                                                );
                                            $sql = $sql . $this->addElementuaBloque(
                                                    $A204AYUNTA,
                                                    $idBlokea,
                                                    $idElementua,
                                                    $idOrdenElementua
                                                );
                                            $idElementua += 1;
                                            $idOrdenElementua += 1;
                                        }
                                    }

                                }
                                if ( $badu == 0 ) {
                                    // Ez dagokio
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            "No corresponde",
                                            "Ez dagokio",
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 0;
                                }
                                $idBlokea += 1;
                                $idOrden += 1;
                            }
                            /****** FIN BESTEAK2 *********************************************************************/

                            /****** HASI BESTEAK3 *********************************************************************/
                            if ( ( $eremuak[ 'besteak3text' ] ) || ( $eremuak[ 'besteak3table' ] ) ) {
                                $sql = $sql . $this->addBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $labelak[ 'besteak3labeles' ],
                                        $labelak[ 'besteak3labeleu' ]
                                    );
                                $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                                $badu = 0;
                                if ( $eremuak[ 'besteak3text' ] ) {
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            $fitxa->getBesteak3es(),
                                            $fitxa->getBesteak3eu(),
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 1;
                                }
                                if ( $eremuak[ "besteak3table" ] ) {

                                    if ( $fitxa->getBesteak3ak() ) {
                                        $doctextes = "<ul>";
                                        $doctexteu = "<ul>";
                                        foreach ( $fitxa->getBesteak3ak() as $bes ) {
                                            $doctextes = $doctextes . "<li>";
                                            $doctexteu = $doctexteu . "<li>";
                                            if ( $bes->getEstekaes() ) {
                                                $doctextes = $doctextes . "<a href='" . $bes->getEstekaes() . "'>" . $bes->getIzenburuaes() . "</a>";
                                            } else {
                                                $doctextes = $doctextes . $bes->getIzenburuaes();
                                            }
                                            if ( $bes->getEstekaeu() ) {
                                                $doctexteu = $doctexteu . "<a href='" . $bes->getEstekaeu() . "'>" . $bes->getIzenburuaeu() . "</a>";
                                            } else {
                                                $doctexteu = $doctexteu . $bes->getIzenburuaeu();
                                            }
                                            $doctextes = $doctextes . "</li>";
                                            $doctexteu = $doctexteu . "</li>";
                                            $badu = 1;
                                        }
                                        $doctextes = $doctextes . "</ul>";
                                        $doctexteu = $doctexteu . "</ul>";

                                        if ( $badu == 1 ) {
                                            $sql = $sql . $this->addElementua(
                                                    $A204AYUNTA,
                                                    $idElementua,
                                                    "Texto",
                                                    $doctextes,
                                                    $doctexteu,
                                                    "PARRAFO"
                                                );
                                            $sql = $sql . $this->addElementuaBloque(
                                                    $A204AYUNTA,
                                                    $idBlokea,
                                                    $idElementua,
                                                    $idOrdenElementua
                                                );
                                            $idElementua += 1;
                                            $idOrdenElementua += 1;
                                        }
                                    }
                                }
                                if ( $badu == 0 ) {
                                    // Ez dagokio
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            "No corresponde",
                                            "Ez dagokio",
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                    $badu = 0;
                                }
                                $idBlokea += 1;
                                $idOrden += 1;
                            }
                            /****** FIN BESTEAK3 *********************************************************************/

                            /****** HASI DATUENBABESA *********************************************************************/
                            if ( ( $eremuak[ 'datuenbabesatext' ] ) || ( $eremuak[ 'datuenbabesatable' ] ) ) {
                                $sql = $sql . $this->addBloque(
                                        $A204AYUNTA,
                                        $idBlokea,
                                        $labelak[ 'datuenbabesalabeles' ],
                                        $labelak[ 'datuenbabesalabeleu' ]
                                    );
                                $sql = $sql . $this->addOrriaBloque( $A204AYUNTA, $idPagina, $idBlokea, $idOrden );

                                $badu = 0;
                                if ( $eremuak[ "datuenbabesatable" ] && $fitxa->getDatuenbabesa() ) {

                                    $textes = $fitxa->getUdala()->getLopdes();

                                    $textes = str_replace(
                                        '$$$fitxa.datuenbabesa.izenaes$$$',
                                        $fitxa->getDatuenbabesa()->getIzenaes(),
                                        $textes
                                    );
                                    $textes = str_replace(
                                        '$$$fitxa.datuenbabesa.kodea$$$',
                                        $fitxa->getDatuenbabesa()->getKodea(),
                                        $textes
                                    );
                                    $textes = str_replace(
                                        '$$$fitxa.datuenbabesa.xedeaes$$$',
                                        $fitxa->getDatuenbabesa()->getXedeaes(),
                                        $textes
                                    );
                                    $textes = str_replace(
                                        '$$$fitxa.datuenbabesa.lagapenakes$$$',
                                        $fitxa->getDatuenbabesa()->getLagapenakes(),
                                        $textes
                                    );

                                    $texteu = $fitxa->getUdala()->getLopdeu();
                                    $texteu = str_replace(
                                        '$$$fitxa.datuenbabesa.izenaeu$$$',
                                        $fitxa->getDatuenbabesa()->getIzenaeu(),
                                        $texteu
                                    );
                                    $texteu = str_replace(
                                        '$$$fitxa.datuenbabesa.kodea$$$',
                                        $fitxa->getDatuenbabesa()->getKodea(),
                                        $texteu
                                    );
                                    $texteu = str_replace(
                                        '$$$fitxa.datuenbabesa.xedeaeu$$$',
                                        $fitxa->getDatuenbabesa()->getXedeaeu(),
                                        $texteu
                                    );
                                    $texteu = str_replace(
                                        '$$$fitxa.datuenbabesa.lagapenakeu$$$',
                                        $fitxa->getDatuenbabesa()->getLagapenakeu(),
                                        $texteu
                                    );
                                    $badu = 1;
                                }


                                if ( $eremuak[ 'datuenbabesatext' ] ) {
                                    $textes = $textes . $fitxa->getDatuenbabesaes();
                                    $texteu = $texteu . $fitxa->getDatuenbabesaeu();
                                    $badu = 1;
                                }

                                if ( $badu == 1 ) {
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            $textes,
                                            $texteu,
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                } else {
                                    $sql = $sql . $this->addElementua(
                                            $A204AYUNTA,
                                            $idElementua,
                                            "Texto",
                                            "No corresponde",
                                            "Ez dagokio",
                                            "PARRAFO"
                                        );
                                    $sql = $sql . $this->addElementuaBloque(
                                            $A204AYUNTA,
                                            $idBlokea,
                                            $idElementua,
                                            $idOrdenElementua
                                        );
                                    $idElementua += 1;
                                    $idOrdenElementua += 1;
                                }
                                $idBlokea += 1;
                                $idOrden += 1;
                            }
                            /****** FIN DATUENBABESA *********************************************************************/

                        }

                        // Esteka sortu Home-an
                        $sql = $sql . $this->addElementua(
                                $A204AYUNTA,
                                $idElementua,
                                'Link-' . $fitxa->getEspedientekodea(),
                                $fitxa->getDeskribapenaes(),
                                $fitxa->getDeskribapenaeu(),
                                $fitxa->getParametroa(),
                                $sortutakoFitxak[ $fitxa->getEspedientekodea() ]
                            );
                        $sql = $sql . $this->addElementuaBloque(
                                $A204AYUNTA,
                                $mapa[ $familia->getId() ],
                                $idElementua,
                                $idOrdenElementua
                            );
                        $idElementua += 1;


                        $idPagina += 1;
                        if ( !$debug ) {
                            $progress->advance();
                        }


                    }
                    /**************************************************************************************************/
                    /**** FIN              ****************************************************************************/
                    /**** addFitxa()       ****************************************************************************/
                    /**************************************************************************************************/
                }

            }
        }


        // Fitxategia sortu
        $fs = new Filesystem();
        try {
            $fs->dumpFile( $filename, $sql );
        } catch ( IOExceptionInterface $e ) {
            $output->writeln("An error occurred while creating your directory at " . $e->getPath());
        }

        if ( !$debug ) {
            $progress->finish();
        }
        $output->writeln('');
        $output->writeln('-----------------------------');
        $output->writeln('Process successfully finished');
        $output->writeln('-----------------------------');
        return Command::SUCCESS;
    }
}
