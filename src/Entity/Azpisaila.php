<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Attribute\UdalaEgiaztatu;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use App\Repository\AzpisailaRepository;

#[UdalaEgiaztatu(userFieldName: "udala_id")]
#[ORM\Table(name: 'azpisaila')]
#[ORM\Index(name: 'saila_id_idx', columns: ['saila_id'])]
#[ORM\Index(name: 'barrutia_id_idx', columns: ['barrutia_id'])]
#[ORM\Index(name: 'eraikina_id_idx', columns: ['eraikina_id'])]
#[ORM\Index(name: 'kalea_id_idx', columns: ['kalea_id'])]
#[ORM\Entity(repositoryClass: AzpisailaRepository::class)]
class Azpisaila implements \Stringable
{
    /**
     * @var integer
     * @Expose
     */
    #[ORM\Column(name: 'id', type: 'bigint')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    /**
     * @var string
     * @Expose
     */
    #[ORM\Column(name: 'kodea', type: 'string', length: 10, nullable: true)]
    private $kodea;

    /**
     * @var string
     * @Expose
     */
    #[ORM\Column(name: 'azpisailaeu', type: 'string', length: 255, nullable: true)]
    private $azpisailaeu;

    /**
     * @var string
     * @Expose
     */
    #[ORM\Column(name: 'azpisailaes', type: 'string', length: 255, nullable: true)]
    private $azpisailaes;

    /**
     * @var string
     */
    #[ORM\Column(name: 'arduraduna', type: 'string', length: 255, nullable: true)]
    private $arduraduna;

    /**
     * @var arduradunahaz
     */
    #[ORM\Column(name: 'arduradunahaz', type: 'string', length: 255, nullable: true)]
    private $arduradunahaz;

    /**
     * @var string
     */
    #[ORM\Column(name: 'email', type: 'string', length: 255, nullable: true)]
    private $email;

    /**
     * @var string
     */
    #[ORM\Column(name: 'telefonoa', type: 'string', length: 255, nullable: true)]
    private $telefonoa;

    /**
     * @var string
     */
    #[ORM\Column(name: 'fax', type: 'string', length: 255, nullable: true)]
    private $fax;

    /**
     * @var string
     */
    #[ORM\Column(name: 'kale_zbkia', type: 'string', length: 50, nullable: true)]
    private $kaleZbkia;

    /**
     * @var string
     */
    #[ORM\Column(name: 'hizkia', type: 'string', length: 2, nullable: true)]
    private $hizkia;

    /**
     * @var string
     */
    #[ORM\Column(name: 'ordutegia', type: 'string', length: 255, nullable: true)]
    private $ordutegia;

    /**
     *  ERLAZIOAK 
     */
    /**
     * @var Udala
     *
     */
    #[ORM\JoinColumn(name: 'udala_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: Udala::class)]
    private $udala;

    /**
     * @var Saila
     */
    #[ORM\JoinColumn(name: 'saila_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: Saila::class, inversedBy: 'azpisailak')]
    protected $saila;


    /**
     * @var Kalea
     *
     *
     */
    #[ORM\JoinColumn(name: 'kalea_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    #[ORM\ManyToOne(targetEntity: Kalea::class, inversedBy: 'azpisailak')]
    private $kalea;

    /**
     * @var Eraikina
     *
     *
     */
    #[ORM\JoinColumn(name: 'eraikina_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    #[ORM\ManyToOne(targetEntity: Eraikina::class)]
    private $eraikina;

    /**
     * @var Barrutia
     *
     *
     */
    #[ORM\JoinColumn(name: 'barrutia_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    #[ORM\ManyToOne(targetEntity: Barrutia::class)]
    private $barrutia;

    /**
     * @var ArrayCollection
     */
    #[ORM\OneToMany(targetEntity: Fitxa::class, mappedBy: 'azpisaila')]
    private $fitxak;



    /**
     *          TOSTRING
     */
    
    public function __toString(): string
    {
        return (string) $this->getAzpisailaeu();
    }

    public function __construct()
    {
        $this->fitxak = new ArrayCollection();
    }




    /**
     * Set kodea
     *
     * @param string $kodea
     *
     * @return Azpisaila
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
     * Set azpisailaeu
     *
     * @param string $azpisailaeu
     *
     * @return Azpisaila
     */
    public function setAzpisailaeu($azpisailaeu)
    {
        $this->azpisailaeu = $azpisailaeu;

        return $this;
    }

    /**
     * Get azpisailaeu
     *
     * @return string
     */
    public function getAzpisailaeu()
    {
        return $this->azpisailaeu;
    }

    /**
     * Set azpisailaes
     *
     * @param string $azpisailaes
     *
     * @return Azpisaila
     */
    public function setAzpisailaes($azpisailaes)
    {
        $this->azpisailaes = $azpisailaes;

        return $this;
    }

    /**
     * Get azpisailaes
     *
     * @return string
     */
    public function getAzpisailaes()
    {
        return $this->azpisailaes;
    }

    /**
     * Set arduraduna
     *
     * @param string $arduraduna
     *
     * @return Azpisaila
     */
    public function setArduraduna($arduraduna)
    {
        $this->arduraduna = $arduraduna;

        return $this;
    }

    /**
     * Get arduraduna
     *
     * @return string
     */
    public function getArduraduna()
    {
        return $this->arduraduna;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Azpisaila
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telefonoa
     *
     * @param string $telefonoa
     *
     * @return Azpisaila
     */
    public function setTelefonoa($telefonoa)
    {
        $this->telefonoa = $telefonoa;

        return $this;
    }

    /**
     * Get telefonoa
     *
     * @return string
     */
    public function getTelefonoa()
    {
        return $this->telefonoa;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return Azpisaila
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set kaleZbkia
     *
     * @param string $kaleZbkia
     *
     * @return Azpisaila
     */
    public function setKaleZbkia($kaleZbkia)
    {
        $this->kaleZbkia = $kaleZbkia;

        return $this;
    }

    /**
     * Get kaleZbkia
     *
     * @return string
     */
    public function getKaleZbkia()
    {
        return $this->kaleZbkia;
    }

    /**
     * Set hizkia
     *
     * @param string $hizkia
     *
     * @return Azpisaila
     */
    public function setHizkia($hizkia)
    {
        $this->hizkia = $hizkia;

        return $this;
    }

    /**
     * Get hizkia
     *
     * @return string
     */
    public function getHizkia()
    {
        return $this->hizkia;
    }

    /**
     * Set ordutegia
     *
     * @param string $ordutegia
     *
     * @return Azpisaila
     */
    public function setOrdutegia($ordutegia)
    {
        $this->ordutegia = $ordutegia;

        return $this;
    }

    /**
     * Get ordutegia
     *
     * @return string
     */
    public function getOrdutegia()
    {
        return $this->ordutegia;
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
     * @return Azpisaila
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
     * Set saila
     *
     * @param \App\Entity\Saila $saila
     *
     * @return Azpisaila
     */
    public function setSaila(\App\Entity\Saila $saila = null)
    {
        $this->saila = $saila;

        return $this;
    }

    /**
     * Get saila
     *
     * @return \App\Entity\Saila
     */
    public function getSaila()
    {
        return $this->saila;
    }

    /**
     * Set kalea
     *
     * @param Kalea $kalea
     *
     * @return Azpisaila
     */
    public function setKalea(Kalea $kalea = null)
    {
        $this->kalea = $kalea;

        return $this;
    }

    /**
     * Get kalea
     *
     * @return Kalea
     */
    public function getKalea()
    {
        return $this->kalea;
    }

    /**
     * Set eraikina
     *
     * @param Eraikina $eraikina
     *
     * @return Azpisaila
     */
    public function setEraikina(Eraikina $eraikina = null)
    {
        $this->eraikina = $eraikina;

        return $this;
    }

    /**
     * Get eraikina
     *
     * @return Eraikina
     */
    public function getEraikina()
    {
        return $this->eraikina;
    }

    /**
     * Set barrutia
     *
     * @param Barrutia $barrutia
     *
     * @return Azpisaila
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
     * Set arduradunahaz
     *
     * @param string $arduradunahaz
     *
     * @return Azpisaila
     */
    public function setArduradunahaz($arduradunahaz)
    {
        $this->arduradunahaz = $arduradunahaz;

        return $this;
    }

    /**
     * Get arduradunahaz
     *
     * @return string
     */
    public function getArduradunahaz()
    {
        return $this->arduradunahaz;
    }

    /**
     * Add fitxak
     *
     * @param Fitxa $fitxak
     *
     * @return Azpisaila
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
