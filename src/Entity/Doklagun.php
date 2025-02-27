<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Attribute\UdalaEgiaztatu;
use App\Repository\DoklagunRepository;

#[UdalaEgiaztatu(userFieldName: "udala_id")]
#[ORM\Table(name: 'doklagun')]
#[ORM\Entity(repositoryClass: DoklagunRepository::class)]
class Doklagun implements \Stringable
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
    #[ORM\Column(name: 'kodea', type: 'string', length: 255, nullable: true)]
    private $kodea;

    /**
     * @var string
     */
    #[ORM\Column(name: 'deskribapenaeu', type: 'text', length: 65535, nullable: true)]
    private $deskribapenaeu;

    /**
     * @var string
     */
    #[ORM\Column(name: 'deskribapenaes', type: 'text', length: 65535, nullable: true)]
    private $deskribapenaes;

    /**
     * @var string
     */
    #[ORM\Column(name: 'estekaeu', type: 'string', length: 255, nullable: true)]
    private $estekaeu;

    /**
     * @var string
     */
    #[ORM\Column(name: 'estekaes', type: 'string', length: 255, nullable: true)]
    private $estekaes;


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
    #[ORM\ManyToMany(targetEntity: Fitxa::class, mappedBy: 'doklagunak', cascade: ['persist'])]
    private $fitxak;



    /**
     *          TOSTRING
     */
    public function __toString(): string
    {
        return (string) $this->getKodea()."-".$this->getDeskribapenaeu();
    }


    /**
     *          Constructor
     */
    public function __construct()
    {
        $this->fitxak = new ArrayCollection();
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
     * Set kodea
     *
     * @param string $kodea
     *
     * @return Doklagun
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
     * Set deskribapenaeu
     *
     * @param string $deskribapenaeu
     *
     * @return Doklagun
     */
    public function setDeskribapenaeu($deskribapenaeu)
    {
        $this->deskribapenaeu = $deskribapenaeu;

        return $this;
    }

    /**
     * Get deskribapenaeu
     *
     * @return string
     */
    public function getDeskribapenaeu()
    {
        return $this->deskribapenaeu;
    }

    /**
     * Set deskribapenaes
     *
     * @param string $deskribapenaes
     *
     * @return Doklagun
     */
    public function setDeskribapenaes($deskribapenaes)
    {
        $this->deskribapenaes = $deskribapenaes;

        return $this;
    }

    /**
     * Get deskribapenaes
     *
     * @return string
     */
    public function getDeskribapenaes()
    {
        return $this->deskribapenaes;
    }

    /**
     * Set estekaeu
     *
     * @param string $estekaeu
     *
     * @return Doklagun
     */
    public function setEstekaeu($estekaeu)
    {
        $this->estekaeu = $estekaeu;

        return $this;
    }

    /**
     * Get estekaeu
     *
     * @return string
     */
    public function getEstekaeu()
    {
        return $this->estekaeu;
    }

    /**
     * Set estekaes
     *
     * @param string $estekaes
     *
     * @return Doklagun
     */
    public function setEstekaes($estekaes)
    {
        $this->estekaes = $estekaes;

        return $this;
    }

    /**
     * Get estekaes
     *
     * @return string
     */
    public function getEstekaes()
    {
        return $this->estekaes;
    }

    /**
     * Set udala
     *
     * @param Udala $udala
     *
     * @return Doklagun
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
     * Add fitxak
     *
     * @param Fitxa $fitxak
     *
     * @return Doklagun
     */
    public function addFitxak(Fitxa $fitxak)
    {
        $this->fitxak[] = $fitxak;

        return $this;
    }

    /**
     * Remove fitxak
     *
     * @param Fitxa $fitxak
     */
    public function removeFitxak(Fitxa $fitxak)
    {
        $this->fitxak->removeElement($fitxak);
    }

    /**
     * Get fitxak
     *
     * @return Collection
     */
    public function getFitxak()
    {
        return $this->fitxak;
    }
}
