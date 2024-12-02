<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Annotation\UdalaEgiaztatu;
use App\Repository\KanalmotaRepository;

/**
 * Kanalmota
 *
 * @ORM\Table(name="kanalmota")
 * @ORM\Entity(repositoryClass=KanalmotaRepository::class)
 * @UdalaEgiaztatu(userFieldName="udala_id")
 */
class Kanalmota
{

    /**
     * @var id
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var motaeu
     *
     * @ORM\Column(name="motaeu", type="string", length=255, nullable=true)
     */
    private $motaeu;

    /**
     * @var motaes
     *
     * @ORM\Column(name="motaes", type="string", length=255, nullable=true)
     */
    private $motaes;

    /**
     * @var esteka
     *
     * @ORM\Column(name="esteka", type="boolean", nullable=true)
     */
    private $esteka;

    /**
     * @var ikonoa
     *
     * @ORM\Column(name="ikonoa", type="string", length=255, nullable=true)
     */
    private $ikonoa;


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
     * @var kanalak[]
     *
     * @ORM\OneToMany(targetEntity="Kanala", mappedBy="kanalmota")
     */
    private $kanalak;


    /**
     *          FUNTZIOAK
     */

    /**
     * @return string
     */

    public function __toString()
    {
        return (string) $this->getMotaeu();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->kanalak = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \App\Entity\Udala $udala
     *
     * @return Kanalmota
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
     * Add kanalak
     *
     * @param \App\Entity\Kanala $kanalak
     *
     * @return Kanalmota
     */
    public function addKanalak(\App\Entity\Kanala $kanalak)
    {
        $this->kanalak[] = $kanalak;

        return $this;
    }

    /**
     * Remove kanalak
     *
     * @param \App\Entity\Kanala $kanalak
     */
    public function removeKanalak(\App\Entity\Kanala $kanalak)
    {
        $this->kanalak->removeElement($kanalak);
    }

    /**
     * Get kanalak
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getKanalak()
    {
        return $this->kanalak;
    }
}
