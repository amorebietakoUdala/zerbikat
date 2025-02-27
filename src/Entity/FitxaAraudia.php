<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FitxaAraudiaRepository;


/**
 * FitxaAraudia
 */
#[ORM\Table(name: 'fitxa_araudia')]
#[ORM\Index(name: 'fitxa_id_idx', columns: ['fitxa_id'])]
#[ORM\Index(name: 'araudia_id_idx', columns: ['araudia_id'])]
#[ORM\Entity(repositoryClass: FitxaAraudiaRepository::class)]
class FitxaAraudia implements \Stringable
{
    /**
     * @var string
     */
    #[ORM\Column(name: 'atalaeu', type: 'string', length: 50, nullable: true)]
    private $atalaeu;

    /**
     * @var string
     */
    #[ORM\Column(name: 'atalaes', type: 'string', length: 50, nullable: true)]
    private $atalaes;

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
    #[ORM\JoinColumn(name: 'udala_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: Udala::class)]
    private $udala;
    
    
    /**
     * @var Fitxa
     *
     *
     */
    #[ORM\JoinColumn(name: 'fitxa_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: Fitxa::class, inversedBy: 'araudiak')]
    private $fitxa;

    /**
     * @var Araudia
     *
     *
     */
    #[ORM\JoinColumn(name: 'araudia_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: Araudia::class, inversedBy: 'fitxak')]
    #[ORM\OrderBy(['kodea' => 'ASC'])]
    private $araudia;

    /**
     *          TOSTRING
     */

    public function __toString(): string
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
     * @param Udala $udala
     *
     * @return FitxaAraudia
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
     * @return FitxaAraudia
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

    /**
     * Set araudia
     *
     * @param Araudia $araudia
     *
     * @return FitxaAraudia
     */
    public function setAraudia(Araudia $araudia = null)
    {
        $this->araudia = $araudia;

        return $this;
    }

    /**
     * Get araudia
     *
     * @return Araudia
     */
    public function getAraudia()
    {
        return $this->araudia;
    }

}
