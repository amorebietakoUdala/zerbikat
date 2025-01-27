<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FitxaProzeduraRepository;

/**
 * FitxaProzedura
 *
 * @ORM\Table(name="fitxa_prozedura", indexes={@ORM\Index(name="fitxa_id_idx", columns={"fitxa_id"}), @ORM\Index(name="prozedura_id_idx", columns={"prozedura_id"})})
 * @ORM\Entity(repositoryClass=FitxaProzeduraRepository::class)
 */
class FitxaProzedura
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ordena", type="bigint", nullable=true)
     */
    private $ordena;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     *          ERLAZIOAK
     */

    /**
     * @var udala
     * @ORM\ManyToOne(targetEntity="Udala")
     * @ORM\JoinColumn(name="udala_id", referencedColumnName="id")
     *
     */
    private $udala;

    /**
     * @ORM\ManyToOne(targetEntity="Prozedura", inversedBy="fitxak")
     * @ORM\JoinColumn(name="prozedura_id", referencedColumnName="id")
     *
     * */
    protected $prozedura;

    /**
     * @ORM\ManyToOne(targetEntity="Fitxa", inversedBy="prozedurak")
     * @ORM\JoinColumn(name="fitxa_id", referencedColumnName="id")
     *
     * */
    protected $fitxa;


    /**
     *          TOSTRING
     */
    public function __toString()
    {
        return (string) $this->getProzedura()->getProzeduraeu();
    }



    /**
     * Set ordena
     *
     * @param integer $ordena
     *
     * @return FitxaProzedura
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
     * @return FitxaProzedura
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
     * Set prozedura
     *
     * @param \App\Entity\Prozedura $prozedura
     *
     * @return FitxaProzedura
     */
    public function setProzedura(\App\Entity\Prozedura $prozedura = null)
    {
        $this->prozedura = $prozedura;

        return $this;
    }

    /**
     * Get prozedura
     *
     * @return \App\Entity\Prozedura
     */
    public function getProzedura()
    {
        return $this->prozedura;
    }

    /**
     * Set fitxa
     *
     * @param \App\Entity\Fitxa $fitxa
     *
     * @return FitxaProzedura
     */
    public function setFitxa(\App\Entity\Fitxa $fitxa = null)
    {
        $this->fitxa = $fitxa;

        return $this;
    }

    /**
     * Get fitxa
     *
     * @return \App\Entity\Fitxa
     */
    public function getFitxa()
    {
        return $this->fitxa;
    }
}
