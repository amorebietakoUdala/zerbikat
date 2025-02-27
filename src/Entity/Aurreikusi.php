<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Attribute\UdalaEgiaztatu;
use App\Repository\AurreikusiRepository;

#[UdalaEgiaztatu(userFieldName: "udala_id")]
#[ORM\Table(name: 'aurreikusi')]
#[ORM\Entity(repositoryClass: AurreikusiRepository::class)]
class Aurreikusi implements \Stringable
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
    #[ORM\Column(name: 'epeaeu', type: 'string', length: 255, nullable: true)]
    private $epeaeu;

    /**
     * @var string
     */
    #[ORM\Column(name: 'epeaes', type: 'string', length: 255, nullable: true)]
    private $epeaes;



    /**
     *          ERLAZIOAK
     */
    public function __toString(): string
    {
        return (string) $this->getEpeaeu();
    }

    /**
     * @var Udala
     *
     */
    #[ORM\JoinColumn(name: 'udala_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: Udala::class)]
    private $udala;

    /**
     *          FUNTZIOAK
     */


    /**
     * Set epeaeu
     *
     * @param string $epeaeu
     * @return Aurreikusi
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
     * @return Aurreikusi
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
     * @param Udala $udala
     * @return Aurreikusi
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
