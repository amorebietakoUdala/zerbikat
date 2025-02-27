<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Attribute\UdalaEgiaztatu;
use Doctrine\Common\Collections\ArrayCollection;

#[UdalaEgiaztatu(userFieldName: "udala_id")]
#[ORM\Table(name: 'barrutia')]
#[ORM\Entity]
class Barrutia implements \Stringable
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
     * @return Barrutia
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
     * @return Barrutia
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
     * Add azpisailak
     *
     * @param Azpisaila $azpisailak
     *
     * @return Barrutia
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
