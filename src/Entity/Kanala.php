<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Annotation\UdalaEgiaztatu;
use App\Repository\KanalaRepository;

/**
 * Kanala
 *
 * @ORM\Table(name="kanala",  indexes={@ORM\Index(name="kanalmota_id_idx", columns={"kanalmota_id"}), @ORM\Index(name="barrutia_id_idx", columns={"barrutia_id"}), @ORM\Index(name="eraikina_id_idx", columns={"eraikina_id"}), @ORM\Index(name="kalea_id_idx", columns={"kalea_id"})})
 * @ORM\Entity(repositoryClass=KanalaRepository::class)
 * @UdalaEgiaztatu(userFieldName="udala_id")
 */
class Kanala
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;    


    /**
     * @var string
     *
     * @ORM\Column(name="izenaeu", type="string", length=255, nullable=true)
     */
    private $izenaeu;

    /**
     * @var string
     *
     * @ORM\Column(name="izenaes", type="string", length=255, nullable=true)
     */
    private $izenaes;

    /**
     * @var string
     *
     * @ORM\Column(name="deskribapenaeu", type="string", length=255, nullable=true)
     */
    private $deskribapenaeu;

    /**
     * @var string
     *
     * @ORM\Column(name="deskribapenaes", type="string", length=255, nullable=true)
     */
    private $deskribapenaes;

    /**
     * @var string
     *
     * @ORM\Column(name="estekaeu", type="string", length=255, nullable=true)
     */
    private $estekaeu;

    /**
     * @var string
     *
     * @ORM\Column(name="estekaes", type="string", length=255, nullable=true)
     */
    private $estekaes;


    /**
     * @var string
     *
     * @ORM\Column(name="telefonoa", type="string", length=255, nullable=true)
     */
    private $telefonoa;


    
    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=255, nullable=true)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="kalezbkia", type="string", length=50, nullable=true)
     */
    private $kalezbkia;

    /**
     * @var string
     *
     * @ORM\Column(name="postakodea", type="string", length=50, nullable=true)
     */
    private $postakodea;

    /**
     * @var string
     *
     * @ORM\Column(name="ordutegia", type="string", length=255, nullable=true)
     */
    private $ordutegia;


    /**
     * @var telematikoa
     *
     * @ORM\Column(name="telematikoa", type="boolean", nullable=true)
     */
    private $telematikoa;

    /**
     * @var erakutsi
     *
     * @ORM\Column(name="erakutsi", type="boolean", nullable=true)
     */
    private $erakutsi;
    
    
    /**
     *  ERLAZIOAK 
     */

    /**
     * @var udala
     * @ORM\ManyToOne(targetEntity="Udala")
     * @ORM\JoinColumn(name="udala_id", referencedColumnName="id",onDelete="CASCADE")
     *
     */
    private $udala;

    /**
     * @var Kanalmota
     * @ORM\ManyToOne(targetEntity="Kanalmota", inversedBy="kanalak")
     * @ORM\JoinColumn(name="kanalmota_id", referencedColumnName="id",onDelete="SET NULL")
     */
    protected $kanalmota;


    /**
     * @var \App\Entity\Kalea
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Kalea",inversedBy="kanalak")
     * @ORM\JoinColumn(name="kalea_id", referencedColumnName="id",onDelete="SET NULL")
     *
     */
    private $kalea;

    /**
     * @var \App\Entity\Eraikina
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Eraikina")
     * @ORM\JoinColumn(name="eraikina_id", referencedColumnName="id",onDelete="SET NULL")
     *
     */
    private $eraikina;

    /**
     * @var \App\Entity\Barrutia
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Barrutia")
     * @ORM\JoinColumn(name="barrutia_id", referencedColumnName="id",onDelete="SET NULL")
     *
     */
    private $barrutia;

    /**
     * @var fitxak[]
     *
     * @ORM\ManyToMany(targetEntity="Fitxa", mappedBy="kanalak", cascade={"persist"})
     */
    private $fitxak;
    
    
    /**
     *
     *      FUNTZIOAK
     *
     */

    public function __toString()
    {
        return (string) $this->getIzenaeu().''.$this->getDeskribapenaeu();
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fitxak = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Kanala
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
     * @return Kanala
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
     * Set deskribapenaeu
     *
     * @param string $deskribapenaeu
     *
     * @return Kanala
     */
    public function setDeskribapenaeu($deskribapenaeu)
    {
        $this->deskribapenaeu = $deskribapenaeu;

        return $this;
    }

    /**
     * Get deskribapenaeu
     *
     * @return string
     */
    public function getDeskribapenaeu()
    {
        return $this->deskribapenaeu;
    }

    /**
     * Set deskribapenaes
     *
     * @param string $deskribapenaes
     *
     * @return Kanala
     */
    public function setDeskribapenaes($deskribapenaes)
    {
        $this->deskribapenaes = $deskribapenaes;

        return $this;
    }

    /**
     * Get deskribapenaes
     *
     * @return string
     */
    public function getDeskribapenaes()
    {
        return $this->deskribapenaes;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Kanala
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set telefonoa
     *
     * @param string $telefonoa
     *
     * @return Kanala
     */
    public function setTelefonoa($telefonoa)
    {
        $this->telefonoa = $telefonoa;

        return $this;
    }

    /**
     * Get telefonoa
     *
     * @return string
     */
    public function getTelefonoa()
    {
        return $this->telefonoa;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return Kanala
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set kalezbkia
     *
     * @param string $kalezbkia
     *
     * @return Kanala
     */
    public function setKalezbkia($kalezbkia)
    {
        $this->kalezbkia = $kalezbkia;

        return $this;
    }

    /**
     * Get kalezbkia
     *
     * @return string
     */
    public function getKalezbkia()
    {
        return $this->kalezbkia;
    }

    /**
     * Set postakodea
     *
     * @param string $postakodea
     *
     * @return Kanala
     */
    public function setPostakodea($postakodea)
    {
        $this->postakodea = $postakodea;

        return $this;
    }

    /**
     * Get postakodea
     *
     * @return string
     */
    public function getPostakodea()
    {
        return $this->postakodea;
    }

    /**
     * Set ordutegia
     *
     * @param string $ordutegia
     *
     * @return Kanala
     */
    public function setOrdutegia($ordutegia)
    {
        $this->ordutegia = $ordutegia;

        return $this;
    }

    /**
     * Get ordutegia
     *
     * @return string
     */
    public function getOrdutegia()
    {
        return $this->ordutegia;
    }

    /**
     * Set udala
     *
     * @param \App\Entity\Udala $udala
     *
     * @return Kanala
     */
    public function setUdala(\App\Entity\Udala $udala = null)
    {
        $this->udala = $udala;

        return $this;
    }

    /**
     * Get udala
     *
     * @return \App\Entity\Udala
     */
    public function getUdala()
    {
        return $this->udala;
    }

    /**
     * Set kanalmota
     *
     * @param \App\Entity\Kanalmota $kanalmota
     *
     * @return Kanala
     */
    public function setKanalmota(\App\Entity\Kanalmota $kanalmota = null)
    {
        $this->kanalmota = $kanalmota;

        return $this;
    }

    /**
     * Get kanalmota
     *
     * @return \App\Entity\Kanalmota
     */
    public function getKanalmota()
    {
        return $this->kanalmota;
    }

    /**
     * Set kalea
     *
     * @param \App\Entity\Kalea $kalea
     *
     * @return Kanala
     */
    public function setKalea(\App\Entity\Kalea $kalea = null)
    {
        $this->kalea = $kalea;

        return $this;
    }

    /**
     * Get kalea
     *
     * @return \App\Entity\Kalea
     */
    public function getKalea()
    {
        return $this->kalea;
    }

    /**
     * Set eraikina
     *
     * @param \App\Entity\Eraikina $eraikina
     *
     * @return Kanala
     */
    public function setEraikina(\App\Entity\Eraikina $eraikina = null)
    {
        $this->eraikina = $eraikina;

        return $this;
    }

    /**
     * Get eraikina
     *
     * @return \App\Entity\Eraikina
     */
    public function getEraikina()
    {
        return $this->eraikina;
    }

    /**
     * Set barrutia
     *
     * @param \App\Entity\Barrutia $barrutia
     *
     * @return Kanala
     */
    public function setBarrutia(\App\Entity\Barrutia $barrutia = null)
    {
        $this->barrutia = $barrutia;

        return $this;
    }

    /**
     * Get barrutia
     *
     * @return \App\Entity\Barrutia
     */
    public function getBarrutia()
    {
        return $this->barrutia;
    }

    /**
     * Add fitxak
     *
     * @param \App\Entity\Fitxa $fitxak
     *
     * @return Kanala
     */
    public function addFitxak(\App\Entity\Fitxa $fitxak)
    {
        $this->fitxak[] = $fitxak;

        return $this;
    }

    /**
     * Remove fitxak
     *
     * @param \App\Entity\Fitxa $fitxak
     */
    public function removeFitxak(\App\Entity\Fitxa $fitxak)
    {
        $this->fitxak->removeElement($fitxak);
    }

    /**
     * Get fitxak
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFitxak()
    {
        return $this->fitxak;
    }

    /**
     * Set estekaeu
     *
     * @param string $estekaeu
     *
     * @return Kanala
     */
    public function setEstekaeu($estekaeu)
    {
        $this->estekaeu = $estekaeu;

        return $this;
    }

    /**
     * Get estekaeu
     *
     * @return string
     */
    public function getEstekaeu()
    {
        return $this->estekaeu;
    }

    /**
     * Set estekaes
     *
     * @param string $estekaes
     *
     * @return Kanala
     */
    public function setEstekaes($estekaes)
    {
        $this->estekaes = $estekaes;

        return $this;
    }

    /**
     * Get estekaes
     *
     * @return string
     */
    public function getEstekaes()
    {
        return $this->estekaes;
    }

    /**
     * Set telematikoa
     *
     * @param boolean $telematikoa
     *
     * @return Kanala
     */
    public function setTelematikoa($telematikoa)
    {
        $this->telematikoa = $telematikoa;

        return $this;
    }

    /**
     * Get telematikoa
     *
     * @return boolean
     */
    public function getTelematikoa()
    {
        return $this->telematikoa;
    }

    /**
     * Set erakutsi
     *
     * @param boolean $erakutsi
     *
     * @return Kanala
     */
    public function setErakutsi($erakutsi)
    {
        $this->erakutsi = $erakutsi;

        return $this;
    }

    /**
     * Get erakutsi
     *
     * @return boolean
     */
    public function getErakutsi()
    {
        return $this->erakutsi;
    }
}
