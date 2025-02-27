<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrdenantzaparrafoaRepository;

/**
 * Ordenantzaparrafoa
 */
#[ORM\Table(name: 'ordenantzaparrafoa')]
#[ORM\Index(name: 'ordenantza_id_idx', columns: ['ordenantza_id'])]
#[ORM\Entity(repositoryClass: OrdenantzaparrafoaRepository::class)]
class Ordenantzaparrafoa
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
    #[ORM\Column(name: 'ordena', type: 'bigint', nullable: true)]
    private $ordena;

    /**
     * @var string
     */
    #[ORM\Column(name: 'testuaeu', type: 'text', length: 65535, nullable: true)]
    private $testuaeu;

    /**
     * @var string
     */
    #[ORM\Column(name: 'testuaes', type: 'text', length: 65535, nullable: true)]
    private $testuaes;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    private $createdAt;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
    private $updatedAt;

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
     * @var Ordenantza
     *
     *
     */
    #[ORM\JoinColumn(name: 'ordenantza_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: Ordenantza::class)]
    private $ordenantza;


    /**
     *          FUNTZIOAK
     */

    /**
     * Set ordena
     *
     * @param integer $ordena
     * @return Ordenantzaparrafoa
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
     * Set testuaeu
     *
     * @param string $testuaeu
     * @return Ordenantzaparrafoa
     */
    public function setTestuaeu($testuaeu)
    {
        $this->testuaeu = $testuaeu;

        return $this;
    }

    /**
     * Get testuaeu
     *
     * @return string 
     */
    public function getTestuaeu()
    {
        return $this->testuaeu;
    }

    /**
     * Set testuaes
     *
     * @param string $testuaes
     * @return Ordenantzaparrafoa
     */
    public function setTestuaes($testuaes)
    {
        $this->testuaes = $testuaes;

        return $this;
    }

    /**
     * Get testuaes
     *
     * @return string 
     */
    public function getTestuaes()
    {
        return $this->testuaes;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Ordenantzaparrafoa
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Ordenantzaparrafoa
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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
     * Set ordenantza
     *
     * @param Ordenantza $ordenantza
     * @return Ordenantzaparrafoa
     */
    public function setOrdenantza(Ordenantza $ordenantza = null)
    {
        $this->ordenantza = $ordenantza;

        return $this;
    }

    /**
     * Get ordenantza
     *
     * @return Ordenantza 
     */
    public function getOrdenantza()
    {
        return $this->ordenantza;
    }

    /**
     * Set udala
     *
     * @param Udala $udala
     * @return Ordenantzaparrafoa
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
}
