<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Annotation\UdalaEgiaztatu;
use App\Repository\AzpiatalaRepository;

/**
 * Azpiatala
 *
 * @ORM\Table(name="azpiatala")
 * @ORM\Entity(repositoryClass=AzpiatalaRepository::class)
 * @UdalaEgiaztatu(userFieldName="udala_id")
 */
class Azpiatala
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
     * @ORM\Column(name="kodea", type="string", length=9, nullable=true)
     */
    private $kodea;

    /**
     * @var string
     *
     * @ORM\Column(name="izenburuaeu", type="string", length=255, nullable=true)
     */
    private $izenburuaeu;

    /**
     * @var string
     *
     * @ORM\Column(name="izenburuaes", type="string", length=255, nullable=true)
     */
    private $izenburuaes;


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


//    /**
//     * @var \App\Entity\Atala
//     *
//     * @ORM\ManyToOne(targetEntity="App\Entity\Atala")
//     * @ORM\JoinColumn(name="atala_id", referencedColumnName="id",onDelete="CASCADE")
//     *
//     */
//    private $atala;

    /**
     * @var kontzeptuak[]
     *
     * @ORM\OneToMany(targetEntity="Kontzeptua", mappedBy="azpiatala",cascade={"persist"})
     */
    private $kontzeptuak;

    /**
     * @var parrafoak[]
     *
     * @ORM\OneToMany(targetEntity="Azpiatalaparrafoa", mappedBy="azpiatala",cascade={"persist"})
     * @ORM\OrderBy({"ordena" = "ASC"})
     */
    private $parrafoak;

    /**
     * @var fitxak[]
     *
     * @ORM\ManyToMany(targetEntity="Fitxa",mappedBy="azpiatalak", cascade={"persist"}))
     */
    private $fitxak;


    /**
     *          TOSTRING
     */
    public function __toString()
    {
        return
            (string) $this->getKodea()."-".$this->getIzenburuaeu();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->kontzeptuak = new ArrayCollection();
        $this->parrafoak = new ArrayCollection();
        $this->fitxak = new ArrayCollection();
    }

    public function addKontzeptua(Kontzeptua $kontzeptua)
    {
        $this->kontzeptuak->add($kontzeptua);
    }

    public function removeKontzeptua(Kontzeptua $kontzeptua)
    {
        $this->kontzeptuak->removeElement($kontzeptua);
    }

    public function addParrafoa(Azpiatalaparrafoa $parrafoa)
    {
        $this->parrafoak->add($parrafoa);
    }

    public function removeParrafoa(Azpiatalaparrafoa $parrafoa)
    {
        $this->parrafoak->removeElement($parrafoa);
    }

    /**
     *          HEMENDIK AURRERA AUTOMATIKOKI SORTUTAKOAK 
     */
    
    
    
    


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
     * @return Azpiatala
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
     * Set izenburuaeu
     *
     * @param string $izenburuaeu
     *
     * @return Azpiatala
     */
    public function setIzenburuaeu($izenburuaeu)
    {
        $this->izenburuaeu = $izenburuaeu;

        return $this;
    }

    /**
     * Get izenburuaeu
     *
     * @return string
     */
    public function getIzenburuaeu()
    {
        return $this->izenburuaeu;
    }

    /**
     * Set izenburuaes
     *
     * @param string $izenburuaes
     *
     * @return Azpiatala
     */
    public function setIzenburuaes($izenburuaes)
    {
        $this->izenburuaes = $izenburuaes;

        return $this;
    }

    /**
     * Get izenburuaes
     *
     * @return string
     */
    public function getIzenburuaes()
    {
        return $this->izenburuaes;
    }

    /**
     * Set udala
     *
     * @param \App\Entity\Udala $udala
     *
     * @return Azpiatala
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
     * Add kontzeptuak
     *
     * @param \App\Entity\Kontzeptua $kontzeptuak
     *
     * @return Azpiatala
     */
    public function addKontzeptuak(\App\Entity\Kontzeptua $kontzeptuak)
    {
        $this->kontzeptuak[] = $kontzeptuak;

        return $this;
    }

    /**
     * Remove kontzeptuak
     *
     * @param \App\Entity\Kontzeptua $kontzeptuak
     */
    public function removeKontzeptuak(\App\Entity\Kontzeptua $kontzeptuak)
    {
        $this->kontzeptuak->removeElement($kontzeptuak);
    }

    /**
     * Get kontzeptuak
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getKontzeptuak()
    {
        return $this->kontzeptuak;
    }

    /**
     * Add parrafoak
     *
     * @param \App\Entity\Azpiatalaparrafoa $parrafoak
     *
     * @return Azpiatala
     */
    public function addParrafoak(\App\Entity\Azpiatalaparrafoa $parrafoak)
    {
        $this->parrafoak[] = $parrafoak;

        return $this;
    }

    /**
     * Remove parrafoak
     *
     * @param \App\Entity\Azpiatalaparrafoa $parrafoak
     */
    public function removeParrafoak(\App\Entity\Azpiatalaparrafoa $parrafoak)
    {
        $this->parrafoak->removeElement($parrafoak);
    }

    /**
     * Get parrafoak
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParrafoak()
    {
        return $this->parrafoak;
    }

    /**
     * Add fitxak
     *
     * @param \App\Entity\Fitxa $fitxak
     *
     * @return Azpiatala
     */
    public function addFitxak(\App\Entity\Fitxa $fitxak)
    {
        $this->fitxak[] = $fitxak;

        return $this;
    }

    /**
     * Remove fitxak
     *
     * @param \App\Entity\Fitxa $fitxak
     */
    public function removeFitxak(\App\Entity\Fitxa $fitxak)
    {
        $this->fitxak->removeElement($fitxak);
    }

    /**
     * Get fitxak
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFitxak()
    {
        return $this->fitxak;
    }
}
