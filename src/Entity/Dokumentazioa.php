<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Annotation\UdalaEgiaztatu;
use App\Repository\DokumentazioaRepository;

/**
 * Dokumentazioa
 *
 * @ORM\Table(name="dokumentazioa", indexes={@ORM\Index(name="dokumentumota_id_idx", columns={"dokumentumota_id"})})
 * @ORM\Entity(repositoryClass=DokumentazioaRepository::class)
 * @UdalaEgiaztatu(userFieldName="udala_id")
 */
class Dokumentazioa
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
     * @ORM\Column(name="kodea", type="string", length=255, nullable=true)
     */
    private $kodea;

    /**
     * @var string
     *
     * @ORM\Column(name="deskribapenaeu", type="text", length=65535, nullable=true)
     */
    private $deskribapenaeu;

    /**
     * @var string
     *
     * @ORM\Column(name="deskribapenaes", type="text", length=65535, nullable=true)
     */
    private $deskribapenaes;

    /**
     * @var string
     *
     * @ORM\Column(name="estekaeu", type="string", length=1024, nullable=true)
     */
    private $estekaeu;

    /**
     * @var string
     *
     * @ORM\Column(name="estekaes", type="string", length=1024, nullable=true)
     */
    private $estekaes;



    /**
     *          ERLAZIOAK
     */

    /**
     * @var udala
     * @ORM\ManyToOne(targetEntity="Udala")
     * @ORM\JoinColumn(name="udala_id", referencedColumnName="id",onDelete="CASCADE")
     *
     */
    private $udala;

    /**
     * @var \App\Entity\Dokumentumota
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Dokumentumota")
     * @ORM\JoinColumn(name="dokumentumota_id", referencedColumnName="id",onDelete="SET NULL")
     * 
     */
    private $dokumentumota;

    /**
     * @var fitxak[]
     *
     * @ORM\ManyToMany(targetEntity="Fitxa", mappedBy="dokumentazioak", cascade="persist"))
     */
    private $fitxak;


    /**
     *          TOSTRING
     */
    public function __toString()
    {
        return (string) $this->getKodea()."-".$this->getDeskribapenaeu();
    }




    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fitxak = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set kodea
     *
     * @param string $kodea
     *
     * @return Dokumentazioa
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
     * Set deskribapenaeu
     *
     * @param string $deskribapenaeu
     *
     * @return Dokumentazioa
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
     * @return Dokumentazioa
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
     * Set estekaeu
     *
     * @param string $estekaeu
     *
     * @return Dokumentazioa
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
     * @return Dokumentazioa
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set udala
     *
     * @param \App\Entity\Udala $udala
     *
     * @return Dokumentazioa
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
     * Set dokumentumota
     *
     * @param \App\Entity\Dokumentumota $dokumentumota
     *
     * @return Dokumentazioa
     */
    public function setDokumentumota(\App\Entity\Dokumentumota $dokumentumota = null)
    {
        $this->dokumentumota = $dokumentumota;

        return $this;
    }

    /**
     * Get dokumentumota
     *
     * @return \App\Entity\Dokumentumota
     */
    public function getDokumentumota()
    {
        return $this->dokumentumota;
    }

    /**
     * Add fitxak
     *
     * @param \App\Entity\Fitxa $fitxak
     *
     * @return Dokumentazioa
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
}
