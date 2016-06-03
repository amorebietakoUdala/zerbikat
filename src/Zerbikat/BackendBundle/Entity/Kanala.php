<?php

namespace Zerbikat\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Kanala
 *
 * @ORM\Table(name="kanala",  indexes={@ORM\Index(name="kanalmota_id_idx", columns={"kanalmota_id"}), @ORM\Index(name="barrutia_id_idx", columns={"barrutia_id"}), @ORM\Index(name="eraikina_id_idx", columns={"eraikina_id"}), @ORM\Index(name="kalea_id_idx", columns={"kalea_id"})})
 * @ORM\Entity
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


    /** @ORM\ManyToOne(targetEntity="Udala") */
    private $udala;

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
     *  ERLAZIOAK 
     */

    /**
     * @var Kanalmota
     * @ORM\ManyToOne(targetEntity="Kanalmota", inversedBy="kanalak")
     */
    protected $kanalmota;


    /**
     * @var \Zerbikat\BackendBundle\Entity\Kalea
     *
     * @ORM\ManyToOne(targetEntity="Zerbikat\BackendBundle\Entity\Kalea",inversedBy="kanalak")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="kalea_id", referencedColumnName="id")
     * })
     */
    private $kalea;

    /**
     * @var \Zerbikat\BackendBundle\Entity\Eraikina
     *
     * @ORM\ManyToOne(targetEntity="Zerbikat\BackendBundle\Entity\Eraikina")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="eraikina_id", referencedColumnName="id")
     * })
     */
    private $eraikina;

    /**
     * @var \Zerbikat\BackendBundle\Entity\Barrutia
     *
     * @ORM\ManyToOne(targetEntity="Zerbikat\BackendBundle\Entity\Barrutia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="barrutia_id", referencedColumnName="id")
     * })
     */
    private $barrutia;

    /**
     * @var fitxak[]
     *
     * @ORM\ManyToMany(targetEntity="Fitxa", mappedBy="kanalak", cascade={"remove"})
     */
    private $fitxak;
    
    
    

    public function __toString()
    {
        return $this->getIzenaeu();
    }

    /**
     *
     *      FUNTZIOAK
     *
     */

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
     * @param \Zerbikat\BackendBundle\Entity\Udala $udala
     *
     * @return Kanala
     */
    public function setUdala(\Zerbikat\BackendBundle\Entity\Udala $udala = null)
    {
        $this->udala = $udala;

        return $this;
    }

    /**
     * Get udala
     *
     * @return \Zerbikat\BackendBundle\Entity\Udala
     */
    public function getUdala()
    {
        return $this->udala;
    }

    /**
     * Set kanalmota
     *
     * @param \Zerbikat\BackendBundle\Entity\Kanalmota $kanalmota
     *
     * @return Kanala
     */
    public function setKanalmota(\Zerbikat\BackendBundle\Entity\Kanalmota $kanalmota = null)
    {
        $this->kanalmota = $kanalmota;

        return $this;
    }

    /**
     * Get kanalmota
     *
     * @return \Zerbikat\BackendBundle\Entity\Kanalmota
     */
    public function getKanalmota()
    {
        return $this->kanalmota;
    }

    /**
     * Set kalea
     *
     * @param \Zerbikat\BackendBundle\Entity\Kalea $kalea
     *
     * @return Kanala
     */
    public function setKalea(\Zerbikat\BackendBundle\Entity\Kalea $kalea = null)
    {
        $this->kalea = $kalea;

        return $this;
    }

    /**
     * Get kalea
     *
     * @return \Zerbikat\BackendBundle\Entity\Kalea
     */
    public function getKalea()
    {
        return $this->kalea;
    }

    /**
     * Set eraikina
     *
     * @param \Zerbikat\BackendBundle\Entity\Eraikina $eraikina
     *
     * @return Kanala
     */
    public function setEraikina(\Zerbikat\BackendBundle\Entity\Eraikina $eraikina = null)
    {
        $this->eraikina = $eraikina;

        return $this;
    }

    /**
     * Get eraikina
     *
     * @return \Zerbikat\BackendBundle\Entity\Eraikina
     */
    public function getEraikina()
    {
        return $this->eraikina;
    }

    /**
     * Set barrutia
     *
     * @param \Zerbikat\BackendBundle\Entity\Barrutia $barrutia
     *
     * @return Kanala
     */
    public function setBarrutia(\Zerbikat\BackendBundle\Entity\Barrutia $barrutia = null)
    {
        $this->barrutia = $barrutia;

        return $this;
    }

    /**
     * Get barrutia
     *
     * @return \Zerbikat\BackendBundle\Entity\Barrutia
     */
    public function getBarrutia()
    {
        return $this->barrutia;
    }

    /**
     * Add fitxak
     *
     * @param \Zerbikat\BackendBundle\Entity\Fitxa $fitxak
     *
     * @return Kanala
     */
    public function addFitxak(\Zerbikat\BackendBundle\Entity\Fitxa $fitxak)
    {
        $this->fitxak[] = $fitxak;

        return $this;
    }

    /**
     * Remove fitxak
     *
     * @param \Zerbikat\BackendBundle\Entity\Fitxa $fitxak
     */
    public function removeFitxak(\Zerbikat\BackendBundle\Entity\Fitxa $fitxak)
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
}