<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Annotation\UdalaEgiaztatu;
use App\Repository\AraumotaRepository;

/**
 * Araumota
 *
 * @ORM\Table(name="araumota")
 * @ORM\Entity(repositoryClass=AraumotaRepository::class)
 * @UdalaEgiaztatu(userFieldName="udala_id")
 */
class Araumota
{
    /**
     * @var string
     *
     * @ORM\Column(name="kodea", type="string", length=255, nullable=true)
     */
    private $kodea;

    /**
     * @var string
     *
     * @ORM\Column(name="motaeu", type="string", length=255, nullable=true)
     */
    private $motaeu;

    /**
     * @var string
     *
     * @ORM\Column(name="motaes", type="string", length=255, nullable=true)
     */
    private $motaes;

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
     * @var araudiak[]
     *
     * @ORM\OneToMany(targetEntity="Araudia", mappedBy="araumota")
     */
    private $araudiak;


    /**
     *      FUNTZIOAK
     */

    public function __toString()
    {
        return (string) $this->getMotaeu();
    }

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->araudiak = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \App\Entity\Udala $udala
     *
     * @return Araumota
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
     * Add araudiak
     *
     * @param \App\Entity\Araudia $araudiak
     *
     * @return Araumota
     */
    public function addAraudiak(\App\Entity\Araudia $araudiak)
    {
        $this->araudiak[] = $araudiak;

        return $this;
    }

    /**
     * Remove araudiak
     *
     * @param \App\Entity\Araudia $araudiak
     */
    public function removeAraudiak(\App\Entity\Araudia $araudiak)
    {
        $this->araudiak->removeElement($araudiak);
    }

    /**
     * Get araudiak
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAraudiak()
    {
        return $this->araudiak;
    }
}
