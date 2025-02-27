<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Attribute\UdalaEgiaztatu;
use App\Repository\KaleaRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Kalea
 *
 */
#[UdalaEgiaztatu(userFieldName: "udala_id")]
#[ORM\Table(name: 'kalea')]
#[ORM\Index(name: 'barrutia_id_idx', columns: ['barrutia_id'])]
#[ORM\Entity(repositoryClass: KaleaRepository::class)]
class Kalea implements \Stringable
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
     * @var string
     */
    #[ORM\Column(name: 'google', type: 'string', length: 255, nullable: true)]
    private $google;

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
    #[ORM\OneToMany(targetEntity: Azpisaila::class, mappedBy: 'kalea')]
    private $azpisailak;

    /**
     * @var ArrayCollection
     */
    #[ORM\OneToMany(targetEntity: Kanala::class, mappedBy: 'kalea')]
    private $kanalak;

    /**
     *          FUNTZIOAK
     */

    /**
     * @return string
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
        $this->kanalak = new ArrayCollection();
    }

    /**
     * Set izena
     *
     * @param string $izena
     *
     * @return Kalea
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
     * Set google
     *
     * @param string $google
     *
     * @return Kalea
     */
    public function setGoogle($google)
    {
        $this->google = $google;

        return $this;
    }

    /**
     * Get google
     *
     * @return string
     */
    public function getGoogle()
    {
        return $this->google;
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
     * @return Kalea
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
     * @return Kalea
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
     * @return Kalea
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

    /**
     * Add kanalak
     *
     * @param Kanala $kanalak
     *
     * @return Kalea
     */
    public function addKanalak(Kanala $kanalak)
    {
        $this->kanalak[] = $kanalak;

        return $this;
    }

    /**
     * Remove kanalak
     *
     * @param Kanala $kanalak
     */
    public function removeKanalak(Kanala $kanalak)
    {
        $this->kanalak->removeElement($kanalak);
    }

    /**
     * Get kanalak
     *
     * @return Collection
     */
    public function getKanalak()
    {
        return $this->kanalak;
    }
}
