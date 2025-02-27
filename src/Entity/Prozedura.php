<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Attribute\UdalaEgiaztatu;
use App\Repository\ProzeduraRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Prozedura
 *
 */
#[UdalaEgiaztatu(userFieldName: "udala_id")]
#[ORM\Table(name: 'prozedura')]
#[ORM\Entity(repositoryClass: ProzeduraRepository::class)]
class Prozedura implements \Stringable
{
    /**
     * @var integer
     */
    #[ORM\Column(name: 'id', type: 'bigint')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'prozeduraeu', type: 'string', length: 255, nullable: true)]
    private $prozeduraeu;

    /**
     * @var string
     */
    #[ORM\Column(name: 'prozeduraes', type: 'string', length: 255, nullable: true)]
    private $prozeduraes;


    /**
     *          ERLAZIOAK
     */
    /**
     * @var Udala
     *
     */
    #[ORM\JoinColumn(name: 'udala_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: Udala::class)]
    private $udala;

    /**
     * @var ArrayCollection
     */
    #[ORM\OneToMany(targetEntity: FitxaProzedura::class, mappedBy: 'prozedura')]
    private $fitxak;


    /**
     *          TOSTRING
     */
    
    public function __toString(): string
    {
        return (string) $this->getProzeduraeu();
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fitxak = new ArrayCollection();
    }

    /**
     * Set prozeduraeu
     *
     * @param string $prozeduraeu
     *
     * @return Prozedura
     */
    public function setProzeduraeu($prozeduraeu)
    {
        $this->prozeduraeu = $prozeduraeu;

        return $this;
    }

    /**
     * Get prozeduraeu
     *
     * @return string
     */
    public function getProzeduraeu()
    {
        return $this->prozeduraeu;
    }

    /**
     * Set prozeduraes
     *
     * @param string $prozeduraes
     *
     * @return Prozedura
     */
    public function setProzeduraes($prozeduraes)
    {
        $this->prozeduraes = $prozeduraes;

        return $this;
    }

    /**
     * Get prozeduraes
     *
     * @return string
     */
    public function getProzeduraes()
    {
        return $this->prozeduraes;
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
     * @return Prozedura
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
     * Add fitxak
     *
     * @param FitxaProzedura $fitxak
     *
     * @return Prozedura
     */
    public function addFitxak(FitxaProzedura $fitxak)
    {
        $this->fitxak[] = $fitxak;

        return $this;
    }

    /**
     * Remove fitxak
     *
     * @param FitxaProzedura $fitxak
     */
    public function removeFitxak(FitxaProzedura $fitxak)
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
