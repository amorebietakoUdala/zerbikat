<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Attribute\UdalaEgiaztatu;
use App\Repository\EraikinaRepository;
use Doctrine\Common\Collections\ArrayCollection;

#[UdalaEgiaztatu(userFieldName: "udala_id")]
#[ORM\Table(name: 'eraikina')]
#[ORM\Index(name: 'barrutia_id_idx', columns: ['barrutia_id'])]
#[ORM\Entity(repositoryClass: EraikinaRepository::class)]
class Eraikina implements \Stringable
{
    /**
     * @var integer
     */
    #[ORM\Column(name: 'id', type: 'bigint')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'izena', type: 'string', length: 255, nullable: true)]
    private $izena;

    /**
     * @var float
     */
    #[ORM\Column(name: 'longitudea', type: 'float', precision: 18, scale: 6, nullable: true)]
    private $longitudea;

    /**
     * @var float
     */
    #[ORM\Column(name: 'latitudea', type: 'float', precision: 18, scale: 6, nullable: true)]
    private $latitudea;



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
     * @var Barrutia
     *
     *
     */
    #[ORM\JoinColumn(name: 'barrutia_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: Barrutia::class)]
    private $barrutia;
        
    /**
     * @var ArrayCollection
     */
    #[ORM\OneToMany(targetEntity: Azpisaila::class, mappedBy: 'saila')]
    private $azpisailak;

    /**
     *          TOSTRING
     */
    public function __toString(): string
    {
        return (string) $this->getIzena();
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->azpisailak = new ArrayCollection();
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
     * @param Udala $udala
     *
     * @return Eraikina
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
     * Set barrutia
     *
     * @param Barrutia $barrutia
     *
     * @return Eraikina
     */
    public function setBarrutia(Barrutia $barrutia = null)
    {
        $this->barrutia = $barrutia;

        return $this;
    }

    /**
     * Get barrutia
     *
     * @return Barrutia
     */
    public function getBarrutia()
    {
        return $this->barrutia;
    }

    /**
     * Add azpisailak
     *
     * @param Azpisaila $azpisailak
     *
     * @return Eraikina
     */
    public function addAzpisailak(Azpisaila $azpisailak)
    {
        $this->azpisailak[] = $azpisailak;

        return $this;
    }

    /**
     * Remove azpisailak
     *
     * @param Azpisaila $azpisailak
     */
    public function removeAzpisailak(Azpisaila $azpisailak)
    {
        $this->azpisailak->removeElement($azpisailak);
    }

    /**
     * Get azpisailak
     *
     * @return Collection
     */
    public function getAzpisailak()
    {
        return $this->azpisailak;
    }
}
