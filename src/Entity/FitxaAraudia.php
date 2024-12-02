<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FitxaAraudiaRepository;


/**
 * FitxaAraudia
 *
 * @ORM\Table(name="fitxa_araudia", indexes={@ORM\Index(name="fitxa_id_idx", columns={"fitxa_id"}), @ORM\Index(name="araudia_id_idx", columns={"araudia_id"})})
 * @ORM\Entity(repositoryClass=FitxaAraudiaRepository::class)
 */
class FitxaAraudia
{
    /**
     * @var string
     *
     * @ORM\Column(name="atalaeu", type="string", length=50, nullable=true)
     */
    private $atalaeu;

    /**
     * @var string
     *
     * @ORM\Column(name="atalaes", type="string", length=50, nullable=true)
     */
    private $atalaes;

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
     * @ORM\JoinColumn(name="udala_id", referencedColumnName="id",onDelete="CASCADE")
     *
     */
    private $udala;
    
    
    /**
     * @var \App\Entity\Fitxa
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Fitxa",inversedBy="araudiak")
     * @ORM\JoinColumn(name="fitxa_id", referencedColumnName="id",onDelete="CASCADE")
     * 
     */
    private $fitxa;

    /**
     * @var \App\Entity\Araudia
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Araudia",inversedBy="fitxak")
     * @ORM\JoinColumn(name="araudia_id", referencedColumnName="id",onDelete="CASCADE")
     * @ORM\OrderBy({"kodea" = "ASC"})
     * 
     */
    private $araudia;

    /**
     *          TOSTRING
     */

    public function __toString()
    {
        return (string) $this->araudia->getKodea()."-".$this->araudia->getArauaeu();
    }



    /**
     * Set atalaeu
     *
     * @param string $atalaeu
     *
     * @return FitxaAraudia
     */
    public function setAtalaeu($atalaeu)
    {
        $this->atalaeu = $atalaeu;

        return $this;
    }

    /**
     * Get atalaeu
     *
     * @return string
     */
    public function getAtalaeu()
    {
        return $this->atalaeu;
    }

    /**
     * Set atalaes
     *
     * @param string $atalaes
     *
     * @return FitxaAraudia
     */
    public function setAtalaes($atalaes)
    {
        $this->atalaes = $atalaes;

        return $this;
    }

    /**
     * Get atalaes
     *
     * @return string
     */
    public function getAtalaes()
    {
        return $this->atalaes;
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
     * @return FitxaAraudia
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
     * Set fitxa
     *
     * @param \App\Entity\Fitxa $fitxa
     *
     * @return FitxaAraudia
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

    /**
     * Set araudia
     *
     * @param \App\Entity\Araudia $araudia
     *
     * @return FitxaAraudia
     */
    public function setAraudia(\App\Entity\Araudia $araudia = null)
    {
        $this->araudia = $araudia;

        return $this;
    }

    /**
     * Get araudia
     *
     * @return \App\Entity\Araudia
     */
    public function getAraudia()
    {
        return $this->araudia;
    }

}
