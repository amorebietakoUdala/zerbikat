<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FitxaKostuaRepository;

/**
 * FitxaKostua
 */
#[ORM\Table(name: 'fitxa_kostua')]
#[ORM\Index(name: 'fitxa_id_idx', columns: ['fitxa_id'])]
#[ORM\Entity(repositoryClass: FitxaKostuaRepository::class)]
class FitxaKostua implements \Stringable
{
    /**
     * @var integer
     */
    #[ORM\Column(name: 'id', type: 'bigint')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    /**
     * @var integer
     */
    #[ORM\Column(name: 'kostua', type: 'bigint')]
    private $kostua;


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
     * @var Fitxa
     *
     *
     */
    #[ORM\JoinColumn(name: 'fitxa_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: Fitxa::class, inversedBy: 'kostuak')]
    private $fitxa;


    /**
     *          TOSTRING
     */

    public function __toString(): string
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
     * @param Udala $udala
     *
     * @return FitxaKostua
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
     * Set fitxa
     *
     * @param Fitxa $fitxa
     *
     * @return FitxaKostua
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
