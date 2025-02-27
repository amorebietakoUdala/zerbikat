<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Attribute\UdalaEgiaztatu;
use App\Repository\KanalmotaRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Kanalmota
 *
 */
#[UdalaEgiaztatu(userFieldName: "udala_id")]
#[ORM\Table(name: 'kanalmota')]
#[ORM\Entity(repositoryClass: KanalmotaRepository::class)]
class Kanalmota implements \Stringable
{

    /**
     * @var id
     */
    #[ORM\Column(name: 'id', type: 'bigint')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    /**
     * @var motaeu
     */
    #[ORM\Column(name: 'motaeu', type: 'string', length: 255, nullable: true)]
    private $motaeu;

    /**
     * @var motaes
     */
    #[ORM\Column(name: 'motaes', type: 'string', length: 255, nullable: true)]
    private $motaes;

    /**
     * @var esteka
     */
    #[ORM\Column(name: 'esteka', type: 'boolean', nullable: true)]
    private $esteka;

    /**
     * @var ikonoa
     */
    #[ORM\Column(name: 'ikonoa', type: 'string', length: 255, nullable: true)]
    private $ikonoa;


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
    #[ORM\OneToMany(targetEntity: Kanala::class, mappedBy: 'kanalmota')]
    private $kanalak;


    /**
     *          FUNTZIOAK
     */

    /**
     * @return string
     */

    public function __toString(): string
    {
        return (string) $this->getMotaeu();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->kanalak = new ArrayCollection();
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
     * Set motaeu
     *
     * @param string $motaeu
     *
     * @return Kanalmota
     */
    public function setMotaeu($motaeu)
    {
        $this->motaeu = $motaeu;

        return $this;
    }

    /**
     * Get motaeu
     *
     * @return string
     */
    public function getMotaeu()
    {
        return $this->motaeu;
    }

    /**
     * Set motaes
     *
     * @param string $motaes
     *
     * @return Kanalmota
     */
    public function setMotaes($motaes)
    {
        $this->motaes = $motaes;

        return $this;
    }

    /**
     * Get motaes
     *
     * @return string
     */
    public function getMotaes()
    {
        return $this->motaes;
    }

    /**
     * Set esteka
     *
     * @param boolean $esteka
     *
     * @return Kanalmota
     */
    public function setEsteka($esteka)
    {
        $this->esteka = $esteka;

        return $this;
    }

    /**
     * Get esteka
     *
     * @return boolean
     */
    public function getEsteka()
    {
        return $this->esteka;
    }

    /**
     * Set ikonoa
     *
     * @param string $ikonoa
     *
     * @return Kanalmota
     */
    public function setIkonoa($ikonoa)
    {
        $this->ikonoa = $ikonoa;

        return $this;
    }

    /**
     * Get ikonoa
     *
     * @return string
     */
    public function getIkonoa()
    {
        return $this->ikonoa;
    }

    /**
     * Set udala
     *
     * @param Udala $udala
     *
     * @return Kanalmota
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
     * Add kanalak
     *
     * @param Kanala $kanalak
     *
     * @return Kanalmota
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
