<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Attribute\UdalaEgiaztatu;
use App\Repository\NorkeskatuRepository;

/**
 * Norkeskatu
 *
 */
#[UdalaEgiaztatu(userFieldName: "udala_id")]
#[ORM\Table(name: 'norkeskatu')]
#[ORM\Entity(repositoryClass: NorkeskatuRepository::class)]
class Norkeskatu implements \Stringable
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
    #[ORM\Column(name: 'norkeu', type: 'string', length: 255, nullable: true)]
    private $norkeu;

    /**
     * @var string
     */
    #[ORM\Column(name: 'norkes', type: 'string', length: 255, nullable: true)]
    private $norkes;




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
    #[ORM\ManyToMany(targetEntity: Fitxa::class, mappedBy: 'norkeskatuak', cascade: ['persist'])]
    private $fitxak;



    /**
     *          TOSTRING
     */
    public function __toString(): string
    {
        return (string) $this->getNorkeu();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fitxak = new ArrayCollection();
    }

    /**
     * Set norkeu
     *
     * @param string $norkeu
     *
     * @return Norkeskatu
     */
    public function setNorkeu($norkeu)
    {
        $this->norkeu = $norkeu;

        return $this;
    }

    /**
     * Get norkeu
     *
     * @return string
     */
    public function getNorkeu()
    {
        return $this->norkeu;
    }

    /**
     * Set norkes
     *
     * @param string $norkes
     *
     * @return Norkeskatu
     */
    public function setNorkes($norkes)
    {
        $this->norkes = $norkes;

        return $this;
    }

    /**
     * Get norkes
     *
     * @return string
     */
    public function getNorkes()
    {
        return $this->norkes;
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
     * @return Norkeskatu
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
     * @param Fitxa $fitxak
     *
     * @return Norkeskatu
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
     * @return Collection
     */
    public function getFitxak()
    {
        return $this->fitxak;
    }
}
