<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Annotation\UdalaEgiaztatu;
use App\Repository\KontzeptumotaRepository;

/**
 * Kontzeptumota
 *
 * @ORM\Table(name="kontzeptumota")
 * @ORM\Entity(repositoryClass=KontzeptumotaRepository::class)
 * @UdalaEgiaztatu(userFieldName="udala_id")
 */
class Kontzeptumota
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
     * @ORM\Column(name="motaeu", type="string", length=255, nullable=true)
     */
    private $motaeu;

    /**
     * @var string
     *
     * @ORM\Column(name="motaes", type="string", length=255, nullable=true)
     */
    private $motaes;


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
     *          TOSTRING
     */
    public function __toString()
    {
        return (string) $this->getMotaeu();
    }

    

    /**
     * Set motaeu
     *
     * @param string $motaeu
     * @return Kontzeptumota
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
     * @return Kontzeptumota
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
     * @return Kontzeptumota
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
}
