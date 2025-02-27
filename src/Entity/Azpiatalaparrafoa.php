<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AzpiatalaparrafoaRepository;

/**
 * Azpiatalaparrafoa
 */
#[ORM\Table(name: 'azpiatalaparrafoa')]
#[ORM\Index(name: 'azpiatala_id_idx', columns: ['azpiatala_id'])]
#[ORM\Entity(repositoryClass: AzpiatalaparrafoaRepository::class)]
class Azpiatalaparrafoa implements \Stringable
{
    /**
     * @var integer
     */
    #[ORM\Column(name: 'id', type: 'bigint')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;
    
    /**
     * @var integer
     */
    #[ORM\Column(name: 'ordena', type: 'bigint', nullable: true)]
    private $ordena;

    /**
     * @var string
     */
    #[ORM\Column(name: 'testuaeu', type: 'text', length: 65535, nullable: true)]
    private $testuaeu;

    /**
     * @var string
     */
    #[ORM\Column(name: 'testuaes', type: 'text', length: 65535, nullable: true)]
    private $testuaes;


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
     * @var Azpiatala
     *
     *
     */
    #[ORM\JoinColumn(name: 'azpiatala_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: Azpiatala::class, inversedBy: 'parrafoak')]
    private $azpiatala;


    /**
     *          TOSTRING
     */
    public function __toString(): string
    {
        return (string) $this->getTestuaeu();
    }



    /**
     * Set ordena
     *
     * @param integer $ordena
     * @return Azpiatalaparrafoa
     */
    public function setOrdena($ordena)
    {
        $this->ordena = $ordena;

        return $this;
    }

    /**
     * Get ordena
     *
     * @return integer 
     */
    public function getOrdena()
    {
        return $this->ordena;
    }

    /**
     * Set testuaeu
     *
     * @param string $testuaeu
     * @return Azpiatalaparrafoa
     */
    public function setTestuaeu($testuaeu)
    {
        $this->testuaeu = $testuaeu;

        return $this;
    }

    /**
     * Get testuaeu
     *
     * @return string 
     */
    public function getTestuaeu()
    {
        return $this->testuaeu;
    }

    /**
     * Set testuaes
     *
     * @param string $testuaes
     * @return Azpiatalaparrafoa
     */
    public function setTestuaes($testuaes)
    {
        $this->testuaes = $testuaes;

        return $this;
    }

    /**
     * Get testuaes
     *
     * @return string 
     */
    public function getTestuaes()
    {
        return $this->testuaes;
    }

    // /**
    //  * Set createdAt
    //  *
    //  * @param \DateTime $createdAt
    //  * @return Azpiatalaparrafoa
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
    //  * @return Azpiatalaparrafoa
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
     * Set azpiatala
     *
     * @param Azpiatala $azpiatala
     * @return Azpiatalaparrafoa
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
     * @return Azpiatalaparrafoa
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
