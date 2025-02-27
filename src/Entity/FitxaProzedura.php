<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FitxaProzeduraRepository;

/**
 * FitxaProzedura
 */
#[ORM\Table(name: 'fitxa_prozedura')]
#[ORM\Index(name: 'fitxa_id_idx', columns: ['fitxa_id'])]
#[ORM\Index(name: 'prozedura_id_idx', columns: ['prozedura_id'])]
#[ORM\Entity(repositoryClass: FitxaProzeduraRepository::class)]
class FitxaProzedura implements \Stringable
{
    /**
     * @var integer
     */
    #[ORM\Column(name: 'ordena', type: 'bigint', nullable: true)]
    private $ordena;

    /**
     * @var integer
     */
    #[ORM\Column(name: 'id', type: 'bigint')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;


    /**
     *          ERLAZIOAK
     */
    /**
     * @var Udala
     *
     */
    #[ORM\JoinColumn(name: 'udala_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: Udala::class)]
    private $udala;

    
    #[ORM\JoinColumn(name: 'prozedura_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: Prozedura::class, inversedBy: 'fitxak')]
    protected $prozedura;

    
    #[ORM\JoinColumn(name: 'fitxa_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: Fitxa::class, inversedBy: 'prozedurak')]
    protected $fitxa;


    /**
     *          TOSTRING
     */
    public function __toString(): string
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
     * @param Udala $udala
     *
     * @return FitxaProzedura
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
     * Set prozedura
     *
     * @param Prozedura $prozedura
     *
     * @return FitxaProzedura
     */
    public function setProzedura(Prozedura $prozedura = null)
    {
        $this->prozedura = $prozedura;

        return $this;
    }

    /**
     * Get prozedura
     *
     * @return Prozedura
     */
    public function getProzedura()
    {
        return $this->prozedura;
    }

    /**
     * Set fitxa
     *
     * @param Fitxa $fitxa
     *
     * @return FitxaProzedura
     */
    public function setFitxa(Fitxa $fitxa = null)
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
}
