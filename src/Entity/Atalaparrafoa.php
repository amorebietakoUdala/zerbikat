<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Annotation\UdalaEgiaztatu;

/**
 * Atalaparrafoa
 *
 * @ORM\Table(name="atalaparrafoa", indexes={@ORM\Index(name="atala_id_idx", columns={"atala_id"})})
 * @ORM\Entity(repositoryClass=Atalaparrafoa::class)
 * @UdalaEgiaztatu(userFieldName="udala_id")
 */
class Atalaparrafoa
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
     * @ORM\Column(name="ordena", type="bigint", nullable=true)
     */
    private $ordena;

    /**
     * @var string
     *
     * @ORM\Column(name="testuaeu", type="text", length=65535, nullable=true)
     */
    private $testuaeu;

    /**
     * @var string
     *
     * @ORM\Column(name="testuaes", type="text", length=65535, nullable=true)
     */
    private $testuaes;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;



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
     * @var \App\Entity\Atala
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Atala")
     * @ORM\JoinColumn(name="atala_id", referencedColumnName="id",onDelete="CASCADE")
     *
     */
    private $atala;




    /**
     * Set ordena
     *
     * @param integer $ordena
     * @return Atalaparrafoa
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
     * @return Atalaparrafoa
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
     * @return Atalaparrafoa
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
     * @return Atalaparrafoa
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
     * @return Atalaparrafoa
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
     * Set atala
     *
     * @param \App\Entity\Atala $atala
     * @return Atalaparrafoa
     */
    public function setAtala(\App\Entity\Atala $atala = null)
    {
        $this->atala = $atala;

        return $this;
    }

    /**
     * Get atala
     *
     * @return \App\Entity\Atala 
     */
    public function getAtala()
    {
        return $this->atala;
    }

    /**
     * Set udala
     *
     * @param \App\Entity\Udala $udala
     * @return Atalaparrafoa
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
}
