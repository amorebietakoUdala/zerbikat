<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Annotation\UdalaEgiaztatu;
use App\Repository\ProzeduraRepository;

/**
 * Prozedura
 *
 * @ORM\Table(name="prozedura")
 * @ORM\Entity(repositoryClass=ProzeduraRepository::class)
 * @UdalaEgiaztatu(userFieldName="udala_id")
 */
class Prozedura
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
     * @ORM\Column(name="prozeduraeu", type="string", length=255, nullable=true)
     */
    private $prozeduraeu;

    /**
     * @var string
     *
     * @ORM\Column(name="prozeduraes", type="string", length=255, nullable=true)
     */
    private $prozeduraes;


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
     * @var fitxak[]
     *
     * @ORM\OneToMany(targetEntity="FitxaProzedura" , mappedBy="prozedura" )
     */
    private $fitxak;


    /**
     *          TOSTRING
     */
    
    public function __toString()
    {
        return (string) $this->getProzeduraeu();
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fitxak = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \App\Entity\Udala $udala
     *
     * @return Prozedura
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
     * Add fitxak
     *
     * @param \App\Entity\FitxaProzedura $fitxak
     *
     * @return Prozedura
     */
    public function addFitxak(\App\Entity\FitxaProzedura $fitxak)
    {
        $this->fitxak[] = $fitxak;

        return $this;
    }

    /**
     * Remove fitxak
     *
     * @param \App\Entity\FitxaProzedura $fitxak
     */
    public function removeFitxak(\App\Entity\FitxaProzedura $fitxak)
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
