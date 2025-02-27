<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Attribute\UdalaEgiaztatu;
use App\Repository\EtiketaRepository;
use Doctrine\Common\Collections\ArrayCollection;

#[UdalaEgiaztatu(userFieldName: "udala_id")]
#[ORM\Table(name: 'etiketa')]
#[ORM\Entity(repositoryClass: EtiketaRepository::class)]
class Etiketa implements \Stringable
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
    #[ORM\Column(name: 'etiketaeu', type: 'string', length: 255, nullable: true)]
    private $etiketaeu;

    /**
     * @var string
     */
    #[ORM\Column(name: 'etiketaes', type: 'string', length: 255, nullable: true)]
    private $etiketaes;


    /**
     *
     *      ERLAZIOAK
     *
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
    #[ORM\ManyToMany(targetEntity: Fitxa::class, mappedBy: 'etiketak', cascade: ['persist'])]
    private $fitxak;
    
    

    public function __toString(): string
    {
        return (string) $this->getEtiketaeu();
    }
    
    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fitxak = new ArrayCollection();
    }

    /**
     * Set etiketaeu
     *
     * @param string $etiketaeu
     *
     * @return Etiketa
     */
    public function setEtiketaeu($etiketaeu)
    {
        $this->etiketaeu = $etiketaeu;

        return $this;
    }

    /**
     * Get etiketaeu
     *
     * @return string
     */
    public function getEtiketaeu()
    {
        return $this->etiketaeu;
    }

    /**
     * Set etiketaes
     *
     * @param string $etiketaes
     *
     * @return Etiketa
     */
    public function setEtiketaes($etiketaes)
    {
        $this->etiketaes = $etiketaes;

        return $this;
    }

    /**
     * Get etiketaes
     *
     * @return string
     */
    public function getEtiketaes()
    {
        return $this->etiketaes;
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
     * @return Etiketa
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
     * @return Etiketa
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
