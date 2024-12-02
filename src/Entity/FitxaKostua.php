<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FitxaKostuaRepository;

/**
 * FitxaKostua
 *
 * @ORM\Table(name="fitxa_kostua", indexes={@ORM\Index(name="fitxa_id_idx", columns={"fitxa_id"})})
 * @ORM\Entity(repositoryClass=FitxaKostuaRepository::class)
 */
class FitxaKostua
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
     * @var integer
     *
     * @ORM\Column(name="kostua", type="bigint")
     */
    private $kostua;


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
     * @ORM\ManyToOne(targetEntity="App\Entity\Fitxa",inversedBy="kostuak")
     * @ORM\JoinColumn(name="fitxa_id", referencedColumnName="id",onDelete="CASCADE")
     * 
     */
    private $fitxa;


    /**
     *          TOSTRING
     */

    public function __toString()
    {
        return (string) $this->kostua." ";
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
     * Set kostua
     *
     * @param integer $kostua
     *
     * @return FitxaKostua
     */
    public function setKostua($kostua)
    {
        $this->kostua = $kostua;

        return $this;
    }

    /**
     * Get kostua
     *
     * @return integer
     */
    public function getKostua()
    {
        return $this->kostua;
    }

    /**
     * Set udala
     *
     * @param \App\Entity\Udala $udala
     *
     * @return FitxaKostua
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
     * @return FitxaKostua
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
