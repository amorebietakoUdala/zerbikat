<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Attribute\UdalaEgiaztatu;
use App\Repository\KontzeptuaRepository;

/**
 * Kontzeptua
 *
 */
#[UdalaEgiaztatu(userFieldName: "udala_id")]
#[ORM\Table(name: 'kontzeptua')]
#[ORM\Index(name: 'azpiatala_id_idx', columns: ['azpiatala_id'])]
#[ORM\Index(name: 'baldintza_id_idx', columns: ['baldintza_id'])]
#[ORM\Index(name: 'kontzeptumota_id_idx', columns: ['kontzeptumota_id'])]
#[ORM\Entity(repositoryClass: KontzeptuaRepository::class)]
class Kontzeptua implements \Stringable
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
    #[ORM\Column(name: 'kodea', type: 'string', length: 9, nullable: true)]
    private $kodea;

    /**
     * @var string
     */
    #[ORM\Column(name: 'kontzeptuaeu', type: 'text', length: 65535, nullable: true)]
    private $kontzeptuaeu;

    /**
     * @var string
     */
    #[ORM\Column(name: 'kontzeptuaes', type: 'text', length: 65535, nullable: true)]
    private $kontzeptuaes;

    /**
     * @var string
     */
    #[ORM\Column(name: 'kopurua', type: 'string', length: 50, nullable: true)]
    private $kopurua;

    /**
     * @var string
     */
    #[ORM\Column(name: 'unitatea', type: 'string', length: 50, nullable: true)]
    private $unitatea;



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
     * @var Kontzeptumota
     *
     *
     */
    #[ORM\JoinColumn(name: 'kontzeptumota_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    #[ORM\ManyToOne(targetEntity: Kontzeptumota::class)]
    private $kontzeptumota;

    /**
     * @var Baldintza
     *
     *
     */
    #[ORM\JoinColumn(name: 'baldintza_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    #[ORM\ManyToOne(targetEntity: Baldintza::class)]
    private $baldintza;

    /**
     * @var Azpiatala
     *
     *
     */
    #[ORM\JoinColumn(name: 'azpiatala_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: Azpiatala::class, inversedBy: 'kontzeptuak')]
    private $azpiatala;


    /**
     *          TOSTRING
     */
    public function __toString(): string
    {
        return (string) $this->getKodea() . " - " . $this->getKontzeptuaeu();
    }

    /**
     * Set kodea
     *
     * @param string $kodea
     * @return Kontzeptua
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
     * Set kontzeptuaeu
     *
     * @param string $kontzeptuaeu
     * @return Kontzeptua
     */
    public function setKontzeptuaeu($kontzeptuaeu)
    {
        $this->kontzeptuaeu = $kontzeptuaeu;

        return $this;
    }

    /**
     * Get kontzeptuaeu
     *
     * @return string 
     */
    public function getKontzeptuaeu()
    {
        return $this->kontzeptuaeu;
    }

    /**
     * Set kontzeptuaes
     *
     * @param string $kontzeptuaes
     * @return Kontzeptua
     */
    public function setKontzeptuaes($kontzeptuaes)
    {
        $this->kontzeptuaes = $kontzeptuaes;

        return $this;
    }

    /**
     * Get kontzeptuaes
     *
     * @return string 
     */
    public function getKontzeptuaes()
    {
        return $this->kontzeptuaes;
    }

    /**
     * Set kopurua
     *
     * @param string $kopurua
     * @return Kontzeptua
     */
    public function setKopurua($kopurua)
    {
        $this->kopurua = $kopurua;

        return $this;
    }

    /**
     * Get kopurua
     *
     * @return string 
     */
    public function getKopurua()
    {
        return $this->kopurua;
    }

    /**
     * Set unitatea
     *
     * @param string $unitatea
     * @return Kontzeptua
     */
    public function setUnitatea($unitatea)
    {
        $this->unitatea = $unitatea;

        return $this;
    }

    /**
     * Get unitatea
     *
     * @return string 
     */
    public function getUnitatea()
    {
        return $this->unitatea;
    }

    // /**
    //  * Set createdAt
    //  *
    //  * @param \DateTime $createdAt
    //  * @return Kontzeptua
    //  */
    // public function setCreatedAt($createdAt)
    // {
    //     $this->createdAt = $createdAt;

    //     return $this;
    // }

    // /**
    //  * Get createdAt
    //  *
    //  * @return \DateTime 
    //  */
    // public function getCreatedAt()
    // {
    //     return $this->createdAt;
    // }

    // /**
    //  * Set updatedAt
    //  *
    //  * @param \DateTime $updatedAt
    //  * @return Kontzeptua
    //  */
    // public function setUpdatedAt($updatedAt)
    // {
    //     $this->updatedAt = $updatedAt;

    //     return $this;
    // }

    // /**
    //  * Get updatedAt
    //  *
    //  * @return \DateTime 
    //  */
    // public function getUpdatedAt()
    // {
    //     return $this->updatedAt;
    // }

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
     * Set kontzeptumota
     *
     * @param Kontzeptumota $kontzeptumota
     * @return Kontzeptua
     */
    public function setKontzeptumota(Kontzeptumota $kontzeptumota = null)
    {
        $this->kontzeptumota = $kontzeptumota;

        return $this;
    }

    /**
     * Get kontzeptumota
     *
     * @return Kontzeptumota 
     */
    public function getKontzeptumota()
    {
        return $this->kontzeptumota;
    }

    /**
     * Set baldintza
     *
     * @param Baldintza $baldintza
     * @return Kontzeptua
     */
    public function setBaldintza(Baldintza $baldintza = null)
    {
        $this->baldintza = $baldintza;

        return $this;
    }

    /**
     * Get baldintza
     *
     * @return Baldintza 
     */
    public function getBaldintza()
    {
        return $this->baldintza;
    }

    /**
     * Set azpiatala
     *
     * @param Azpiatala $azpiatala
     * @return Kontzeptua
     */
    public function setAzpiatala(Azpiatala $azpiatala = null)
    {
        $this->azpiatala = $azpiatala;

        return $this;
    }

    /**
     * Get azpiatala
     *
     * @return Azpiatala 
     */
    public function getAzpiatala()
    {
        return $this->azpiatala;
    }

    /**
     * Set udala
     *
     * @param Udala $udala
     * @return Kontzeptua
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
