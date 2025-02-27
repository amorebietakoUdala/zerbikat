<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use JMS\Serializer\Annotation\ExclusionPolicy;
use App\Attribute\UdalaEgiaztatu;
use App\Repository\FitxafamiliaRepository;

/**
 * Fitxafamilia
 *
 */
#[ExclusionPolicy("all")]
#[UdalaEgiaztatu(userFieldName: "udala_id")]
#[ORM\Table(name: 'fitxa_familia_erlazioak')]
#[ORM\Entity(repositoryClass: FitxafamiliaRepository::class)]
class Fitxafamilia implements \Stringable
{
    /**
     * @var integer
     */
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;

    /**
     * @var int
     */
    #[ORM\Column(name: 'ordena', type: 'integer', nullable: true)]
    private $ordena=0;

    /**************************************************************************************************************
     **************************************************************************************************************
     ******************      ERLAZIOAK
     **************************************************************************************************************
     *************************************************************************************************************/
    #[ORM\JoinColumn(name: 'familia_id', referencedColumnName: 'id', nullable: false, onDelete: 'cascade')]
    #[ORM\ManyToOne(targetEntity: Familia::class, inversedBy: 'fitxafamilia')]
    #[OrderBy(['ordena' => 'ASC'])]
    protected $familia;

    #[ORM\JoinColumn(name: 'fitxa_id', referencedColumnName: 'id', nullable: false, onDelete: 'cascade')]
    #[ORM\ManyToOne(targetEntity: Fitxa::class, inversedBy: 'fitxafamilia')]
    protected $fitxa;

    /**
     * @var Udala
     *
     */
    #[ORM\JoinColumn(name: 'udala_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: Udala::class)]
    private $udala;

    /**************************************************************************************************************
     **************************************************************************************************************
     ******************      ERLAZIOAK FIN
     **************************************************************************************************************
     *************************************************************************************************************/


    public function __toString (): string
    {
        return (string) "";
    }

    /**
     * Constructor
     */
    public function __construct ()
    {

    }





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
     * Set ordena
     *
     * @param integer $ordena
     *
     * @return Fitxafamilia
     */
    public function setOrdena($ordena)
    {
        $this->ordena = $ordena;

        return $this;
    }

    /**
     * Get ordena
     *
     * @return integer
     */
    public function getOrdena()
    {
        return $this->ordena;
    }

    /**
     * Set familia
     *
     * @param Familia $familia
     *
     * @return Fitxafamilia
     */
    public function setFamilia(Familia $familia)
    {
        $this->familia = $familia;

        return $this;
    }

    /**
     * Get familia
     *
     * @return Familia
     */
    public function getFamilia()
    {
        return $this->familia;
    }

    /**
     * Set fitxa
     *
     * @param Fitxa $fitxa
     *
     * @return Fitxafamilia
     */
    public function setFitxa(Fitxa $fitxa)
    {
        $this->fitxa = $fitxa;

        return $this;
    }

    /**
     * Get fitxa
     *
     * @return Fitxa
     */
    public function getFitxa()
    {
        return $this->fitxa;
    }

    /**
     * Set udala
     *
     * @param Udala $udala
     *
     * @return Fitxafamilia
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
}
