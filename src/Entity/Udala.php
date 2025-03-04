<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use App\Repository\UdalaRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Udala
 *
 */
#[ExclusionPolicy("all")]
#[ORM\Table(name: 'udala')]
#[ORM\Entity(repositoryClass: UdalaRepository::class)]
class Udala implements \Stringable
{
    /**
     * @var integer
     */
    #[Expose()]
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;

    /**
     * @var string
     */
    #[Expose()]
    #[ORM\Column(name: 'izenaeu', type: 'string', length: 255)]
    private $izenaeu;

    /**
     * @var string
     */
    #[Expose()]
    #[ORM\Column(name: 'izenaes', type: 'string', length: 255)]
    private $izenaes;

    /**
     * @var string
     */
    #[Expose()]
    #[ORM\Column(name: 'kodea', type: 'string', length: 255)]
    private $kodea;

    /**
     * @var string
     */
    #[Expose()]
    #[ORM\Column(name: 'logoa', type: 'string', length: 255, nullable: true)]
    private $logoa;

    /**
     * @var string
     */
    #[Expose()]
    #[ORM\Column(name: 'ifk', type: 'string', length: 255, nullable: true)]
    private $ifk;

    /**
     * @var string
     */
    #[Expose()]
    #[ORM\Column(name: 'izendapenaeu', type: 'string', length: 255, nullable: true)]
    private $izendapenaeu;

    /**
     * @var string
     */
    #[Expose()]
    #[ORM\Column(name: 'izendapenaes', type: 'string', length: 255, nullable: true)]
    private $izendapenaes;


    /**
     * @var string
     */
    #[Expose()]
    #[ORM\Column(name: 'lopdeu', type: 'text', length: 65535, nullable: true)]
    private $lopdeu;

    /**
     * @var string
     */
    #[Expose()]
    #[ORM\Column(name: 'lopdes', type: 'text', length: 65535, nullable: true)]
    private $lopdes;

    /**
     * @var integer
     */
    #[ORM\Column(name: 'orrikatzea', type: 'bigint', nullable: false)]
    private $orrikatzea = 25;


    /**
     * @var zergaor
     */
    #[ORM\Column(name: 'zergaor', type: 'boolean', nullable: true, options: ['default' => false])]
    private $zergaor;


    #[ORM\JoinColumn(name: 'espedientekudeaketa_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    #[ORM\ManyToOne(targetEntity: Espedientekudeaketa::class)]
    private $espedientekudeaketa;

    #[Expose()]
    #[ORM\OneToOne(targetEntity: Eremuak::class, mappedBy: 'udala', fetch: 'EAGER')]
    protected $eremuak;

    /**
     * @var ArrayCollection
     */
    #[ORM\OneToMany(targetEntity: Fitxa::class, mappedBy: 'udala')]
    private $fitxak;

    /**
     *          TOSTRING
     */
    public function __toString(): string
    {
        return (string) $this->getKodea() . " - " . $this->getIzenaeu();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fitxak = new ArrayCollection();
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
     * Set izenaeu
     *
     * @param string $izenaeu
     *
     * @return Udala
     */
    public function setIzenaeu($izenaeu)
    {
        $this->izenaeu = $izenaeu;

        return $this;
    }

    /**
     * Get izenaeu
     *
     * @return string
     */
    public function getIzenaeu()
    {
        return $this->izenaeu;
    }

    /**
     * Set izenaes
     *
     * @param string $izenaes
     *
     * @return Udala
     */
    public function setIzenaes($izenaes)
    {
        $this->izenaes = $izenaes;

        return $this;
    }

    /**
     * Get izenaes
     *
     * @return string
     */
    public function getIzenaes()
    {
        return $this->izenaes;
    }

    /**
     * Set kodea
     *
     * @param string $kodea
     *
     * @return Udala
     */
    public function setKodea($kodea)
    {
        $this->kodea = $kodea;

        return $this;
    }

    /**
     * Get kodea
     *
     * @return string
     */
    public function getKodea()
    {
        return $this->kodea;
    }

    /**
     * Set logoa
     *
     * @param string $logoa
     *
     * @return Udala
     */
    public function setLogoa($logoa)
    {
        $this->logoa = $logoa;

        return $this;
    }

    /**
     * Get logoa
     *
     * @return string
     */
    public function getLogoa()
    {
        return $this->logoa;
    }

    /**
     * Set ifk
     *
     * @param string $ifk
     *
     * @return Udala
     */
    public function setIfk($ifk)
    {
        $this->ifk = $ifk;

        return $this;
    }

    /**
     * Get ifk
     *
     * @return string
     */
    public function getIfk()
    {
        return $this->ifk;
    }

    /**
     * Set izendapenaeu
     *
     * @param string $izendapenaeu
     *
     * @return Udala
     */
    public function setIzendapenaeu($izendapenaeu)
    {
        $this->izendapenaeu = $izendapenaeu;

        return $this;
    }

    /**
     * Get izendapenaeu
     *
     * @return string
     */
    public function getIzendapenaeu()
    {
        return $this->izendapenaeu;
    }

    /**
     * Set izendapenaes
     *
     * @param string $izendapenaes
     *
     * @return Udala
     */
    public function setIzendapenaes($izendapenaes)
    {
        $this->izendapenaes = $izendapenaes;

        return $this;
    }

    /**
     * Get izendapenaes
     *
     * @return string
     */
    public function getIzendapenaes()
    {
        return $this->izendapenaes;
    }

    /**
     * Set lopdeu
     *
     * @param string $lopdeu
     *
     * @return Udala
     */
    public function setLopdeu($lopdeu)
    {
        $this->lopdeu = $lopdeu;

        return $this;
    }

    /**
     * Get lopdeu
     *
     * @return string
     */
    public function getLopdeu()
    {
        return $this->lopdeu;
    }

    /**
     * Set lopdes
     *
     * @param string $lopdes
     *
     * @return Udala
     */
    public function setLopdes($lopdes)
    {
        $this->lopdes = $lopdes;

        return $this;
    }

    /**
     * Get lopdes
     *
     * @return string
     */
    public function getLopdes()
    {
        return $this->lopdes;
    }

    /**
     * Set espedientekudeaketa
     *
     * @param UdalaEspedientekudeaketa $espedientekudeaketa
     *
     * @return Udala
     */
    public function setEspedientekudeaketa(Espedientekudeaketa $espedientekudeaketa = null)
    {
        $this->espedientekudeaketa = $espedientekudeaketa;

        return $this;
    }

    /**
     * Get espedientekudeaketa
     *
     * @return UdalaEspedientekudeaketa
     */
    public function getEspedientekudeaketa()
    {
        return $this->espedientekudeaketa;
    }

    /**
     * Set eremuak
     *
     * @param Eremuak $eremuak
     *
     * @return Udala
     */
    public function setEremuak(Eremuak $eremuak = null)
    {
        $this->eremuak = $eremuak;

        return $this;
    }

    /**
     * Get eremuak
     *
     * @return Eremuak
     */
    public function getEremuak()
    {
        return $this->eremuak;
    }

    /**
     * Add fitxak
     *
     * @param Fitxa $fitxak
     *
     * @return Udala
     */
    public function addFitxak(Fitxa $fitxak)
    {
        $this->fitxak[] = $fitxak;

        return $this;
    }

    /**
     * Remove fitxak
     *
     * @param Fitxa $fitxak
     */
    public function removeFitxak(Fitxa $fitxak)
    {
        $this->fitxak->removeElement($fitxak);
    }

    /**
     * Get fitxak
     *
     * @return ArrayCollection
     */
    public function getFitxak()
    {
        return $this->fitxak;
    }

    /**
     * Set orrikatzea
     *
     * @param integer $orrikatzea
     *
     * @return Udala
     */
    public function setOrrikatzea($orrikatzea)
    {
        $this->orrikatzea = $orrikatzea;

        return $this;
    }

    /**
     * Get orrikatzea
     *
     * @return integer
     */
    public function getOrrikatzea()
    {
        return $this->orrikatzea;
    }

    /**
     * Set zergaor
     *
     * @param boolean $zergaor
     *
     * @return Udala
     */
    public function setZergaor($zergaor)
    {
        $this->zergaor = $zergaor;

        return $this;
    }

    /**
     * Get zergaor
     *
     * @return boolean
     */
    public function getZergaor()
    {
        return $this->zergaor;
    }
}
