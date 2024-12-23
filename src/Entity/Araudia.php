<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Annotation\UdalaEgiaztatu;
use App\Repository\AraudiaRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Araudia
 *
 * @ORM\Table(name="araudia", indexes={@ORM\Index(name="araumota_id_idx", columns={"araumota_id"})})
 * @ORM\Entity(repositoryClass=AraudiaRepository::class)
 * @UdalaEgiaztatu(userFieldName="udala_id")
 */
class Araudia
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
     * @ORM\Column(name="arauaeu", type="string", length=1024, nullable=true)
     */
    private $arauaeu;

    /**
     * @var string
     *
     * @ORM\Column(name="arauaes", type="string", length=1024, nullable=true)
     */
    private $arauaes;

    /**
     * @var string
     *
     * @ORM\Column(name="estekaeu", type="string", length=1000, nullable=true)
     */
    private $estekaeu;

    /**
     * @var string
     *
     * @ORM\Column(name="estekaes", type="string", length=1000, nullable=true)
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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="FitxaAraudia", mappedBy="araudia")
     */
    private $fitxak;

    /**
     * @var Araumota
     *
     * @ORM\ManyToOne(targetEntity="Araumota",inversedBy="araudiak")
     * @ORM\JoinColumn(name="araumota_id", referencedColumnName="id",onDelete="SET NULL")
     *
     */
    private $araumota;



    /**
     *      FUNTZIOAK
     */

    public function __toString()
    {
        return (string) $this->getKodea() . "-" . $this->getArauaeu();
    }



    /**
     * Set kodea
     *
     * @param string $kodea
     *
     * @return Araudia
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
     * Set arauaeu
     *
     * @param string $arauaeu
     *
     * @return Araudia
     */
    public function setArauaeu($arauaeu)
    {
        $this->arauaeu = $arauaeu;

        return $this;
    }

    /**
     * Get arauaeu
     *
     * @return string
     */
    public function getArauaeu()
    {
        return $this->arauaeu;
    }

    /**
     * Set arauaes
     *
     * @param string $arauaes
     *
     * @return Araudia
     */
    public function setArauaes($arauaes)
    {
        $this->arauaes = $arauaes;

        return $this;
    }

    /**
     * Get arauaes
     *
     * @return string
     */
    public function getArauaes()
    {
        return $this->arauaes;
    }

    /**
     * Set estekaeu
     *
     * @param string $estekaeu
     *
     * @return Araudia
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
     * @return Araudia
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
     * @param Udala $udala
     *
     * @return Araudia
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
     * Set araumota
     *
     * @param Araumota $araumota
     *
     * @return Araudia
     */
    public function setAraumota(Araumota $araumota = null)
    {
        $this->araumota = $araumota;

        return $this;
    }

    /**
     * Get araumota
     *
     * @return Araumota
     */
    public function getAraumota()
    {
        return $this->araumota;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fitxak = new ArrayCollection();
    }

    /**
     * Add fitxak
     *
     * @param FitxaAraudia $fitxak
     *
     * @return Araudia
     */
    public function addFitxak(FitxaAraudia $fitxak)
    {
        $this->fitxak[] = $fitxak;

        return $this;
    }

    /**
     * Remove fitxak
     *
     * @param FitxaAraudia $fitxak
     */
    public function removeFitxak(FitxaAraudia $fitxak)
    {
        $this->fitxak->removeElement($fitxak);
    }

    /**
     * Get fitxak
     *
     * @return Collection
     */
    public function getFitxak()
    {
        return $this->fitxak;
    }
}
