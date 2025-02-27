<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Attribute\UdalaEgiaztatu;
use App\Repository\NorkebatziRepository;
use JMS\Serializer\Annotation\Expose;

#[UdalaEgiaztatu(userFieldName: "udala_id")]
#[ORM\Table(name: 'norkebatzi')]
#[ORM\Entity(repositoryClass: NorkebatziRepository::class)]
class Norkebatzi implements \Stringable
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
     *
     * @Expose
     */
    #[ORM\Column(name: 'norkeu', type: 'string', length: 255, nullable: true)]
    private $norkeu;

    /**
     * @var string
     *
     * @Expose
     */
    #[ORM\Column(name: 'norkes', type: 'string', length: 255, nullable: true)]
    private $norkes;



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
     *          TOSTRING
     */
    public function __toString(): string
    {
        return (string) $this->getNorkeu();
    }

    /**
     * Set norkeu
     *
     * @param string $norkeu
     * @return Norkebatzi
     */
    public function setNorkeu($norkeu)
    {
        $this->norkeu = $norkeu;

        return $this;
    }

    /**
     * Get norkeu
     *
     * @return string 
     */
    public function getNorkeu()
    {
        return $this->norkeu;
    }

    /**
     * Set norkes
     *
     * @param string $norkes
     * @return Norkebatzi
     */
    public function setNorkes($norkes)
    {
        $this->norkes = $norkes;

        return $this;
    }

    /**
     * Get norkes
     *
     * @return string 
     */
    public function getNorkes()
    {
        return $this->norkes;
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
     * @return Norkebatzi
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
