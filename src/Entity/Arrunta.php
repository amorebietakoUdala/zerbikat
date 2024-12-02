<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Annotation\UdalaEgiaztatu;
use App\Repository\ArruntaRepository;
/**
 * Arrunta
 *
 * @ORM\Table(name="arrunta")
 * @ORM\Entity(repositoryClass=ArruntaRepository::class)
 * @UdalaEgiaztatu(userFieldName="udala_id")
 */
class Arrunta
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
     * @ORM\Column(name="epeaeu", type="string", length=255, nullable=true)
     */
    private $epeaeu;

    /**
     * @var string
     *
     * @ORM\Column(name="epeaes", type="string", length=255, nullable=true)
     */
    private $epeaes;


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
     *          FUNTZIOAK
     */
    public function __toString()
    {
        return (string) $this->getEpeaeu();
    }


    /**
     * Set epeaeu
     *
     * @param string $epeaeu
     * @return Arrunta
     */
    public function setEpeaeu($epeaeu)
    {
        $this->epeaeu = $epeaeu;

        return $this;
    }

    /**
     * Get epeaeu
     *
     * @return string 
     */
    public function getEpeaeu()
    {
        return $this->epeaeu;
    }

    /**
     * Set epeaes
     *
     * @param string $epeaes
     * @return Arrunta
     */
    public function setEpeaes($epeaes)
    {
        $this->epeaes = $epeaes;

        return $this;
    }

    /**
     * Get epeaes
     *
     * @return string 
     */
    public function getEpeaes()
    {
        return $this->epeaes;
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
     * @return Arrunta
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
