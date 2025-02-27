<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Attribute\UdalaEgiaztatu;
use App\Repository\AraumotaRepository;
use Doctrine\Common\Collections\ArrayCollection;

#[UdalaEgiaztatu(userFieldName: "udala_id")]
#[ORM\Table(name: 'araumota')]
#[ORM\Entity(repositoryClass: AraumotaRepository::class)]
class Araumota implements \Stringable
{
    /**
     * @var string
     */
    #[ORM\Column(name: 'kodea', type: 'string', length: 255, nullable: true)]
    private $kodea;

    /**
     * @var string
     */
    #[ORM\Column(name: 'motaeu', type: 'string', length: 255, nullable: true)]
    private $motaeu;

    /**
     * @var string
     */
    #[ORM\Column(name: 'motaes', type: 'string', length: 255, nullable: true)]
    private $motaes;

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
     * @var ArrayCollection
     */
    #[ORM\OneToMany(targetEntity: Araudia::class, mappedBy: 'araumota')]
    private $araudiak;


    /**
     *      FUNTZIOAK
     */

    public function __toString(): string
    {
        return (string) $this->getMotaeu();
    }

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->araudiak = new ArrayCollection();
    }

    /**
     * Set kodea
     *
     * @param string $kodea
     *
     * @return Araumota
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
     * Set motaeu
     *
     * @param string $motaeu
     *
     * @return Araumota
     */
    public function setMotaeu($motaeu)
    {
        $this->motaeu = $motaeu;

        return $this;
    }

    /**
     * Get motaeu
     *
     * @return string
     */
    public function getMotaeu()
    {
        return $this->motaeu;
    }

    /**
     * Set motaes
     *
     * @param string $motaes
     *
     * @return Araumota
     */
    public function setMotaes($motaes)
    {
        $this->motaes = $motaes;

        return $this;
    }

    /**
     * Get motaes
     *
     * @return string
     */
    public function getMotaes()
    {
        return $this->motaes;
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
     * @return Araumota
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
     * Add araudiak
     *
     * @param Araudia $araudiak
     *
     * @return Araumota
     */
    public function addAraudiak(Araudia $araudiak)
    {
        $this->araudiak[] = $araudiak;

        return $this;
    }

    /**
     * Remove araudiak
     *
     * @param Araudia $araudiak
     */
    public function removeAraudiak(Araudia $araudiak)
    {
        $this->araudiak->removeElement($araudiak);
    }

    /**
     * Get araudiak
     *
     * @return Collection
     */
    public function getAraudiak()
    {
        return $this->araudiak;
    }
}
