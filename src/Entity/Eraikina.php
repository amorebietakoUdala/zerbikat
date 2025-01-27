<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Annotation\UdalaEgiaztatu;
use App\Repository\EraikinaRepository;

/**
 * Eraikina
 *
 * @ORM\Table(name="eraikina", indexes={@ORM\Index(name="barrutia_id_idx", columns={"barrutia_id"})})
 * @ORM\Entity(repositoryClass=EraikinaRepository::class)
 * @UdalaEgiaztatu(userFieldName="udala_id")
 */
class Eraikina
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
     * @ORM\Column(name="izena", type="string", length=255, nullable=true)
     */
    private $izena;

    /**
     * @var float
     *
     * @ORM\Column(name="longitudea", type="float", precision=18, scale=6, nullable=true)
     */
    private $longitudea;

    /**
     * @var float
     *
     * @ORM\Column(name="latitudea", type="float", precision=18, scale=6, nullable=true)
     */
    private $latitudea;



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
     * @var \App\Entity\Barrutia
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Barrutia")
     * @ORM\JoinColumn(name="barrutia_id", referencedColumnName="id",onDelete="CASCADE")
     *
     */
    private $barrutia;
        
    /**
     * @var azpisailak[]
     *
     * @ORM\OneToMany(targetEntity="Azpisaila", mappedBy="saila")
     */
    private $azpisailak;

    /**
     *          TOSTRING
     */
    public function __toString()
    {
        return (string) $this->getIzena();
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->azpisailak = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set izena
     *
     * @param string $izena
     *
     * @return Eraikina
     */
    public function setIzena($izena)
    {
        $this->izena = $izena;

        return $this;
    }

    /**
     * Get izena
     *
     * @return string
     */
    public function getIzena()
    {
        return $this->izena;
    }

    /**
     * Set longitudea
     *
     * @param float $longitudea
     *
     * @return Eraikina
     */
    public function setLongitudea($longitudea)
    {
        $this->longitudea = $longitudea;

        return $this;
    }

    /**
     * Get longitudea
     *
     * @return float
     */
    public function getLongitudea()
    {
        return $this->longitudea;
    }

    /**
     * Set latitudea
     *
     * @param float $latitudea
     *
     * @return Eraikina
     */
    public function setLatitudea($latitudea)
    {
        $this->latitudea = $latitudea;

        return $this;
    }

    /**
     * Get latitudea
     *
     * @return float
     */
    public function getLatitudea()
    {
        return $this->latitudea;
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
     * @return Eraikina
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
     * Set barrutia
     *
     * @param \App\Entity\Barrutia $barrutia
     *
     * @return Eraikina
     */
    public function setBarrutia(\App\Entity\Barrutia $barrutia = null)
    {
        $this->barrutia = $barrutia;

        return $this;
    }

    /**
     * Get barrutia
     *
     * @return \App\Entity\Barrutia
     */
    public function getBarrutia()
    {
        return $this->barrutia;
    }

    /**
     * Add azpisailak
     *
     * @param \App\Entity\Azpisaila $azpisailak
     *
     * @return Eraikina
     */
    public function addAzpisailak(\App\Entity\Azpisaila $azpisailak)
    {
        $this->azpisailak[] = $azpisailak;

        return $this;
    }

    /**
     * Remove azpisailak
     *
     * @param \App\Entity\Azpisaila $azpisailak
     */
    public function removeAzpisailak(\App\Entity\Azpisaila $azpisailak)
    {
        $this->azpisailak->removeElement($azpisailak);
    }

    /**
     * Get azpisailak
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAzpisailak()
    {
        return $this->azpisailak;
    }
}
