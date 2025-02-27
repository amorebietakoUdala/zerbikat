<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Attribute\UdalaEgiaztatu;
use App\Repository\IsiltasunAdministratiboaRepository;

/**
 * IsiltasunAdministratiboa
 *
 */
#[UdalaEgiaztatu(userFieldName: "udala_id")]
#[ORM\Table(name: 'isiltasunadministratiboa')]
#[ORM\Entity(repositoryClass: IsiltasunAdministratiboaRepository::class)]
class IsiltasunAdministratiboa implements \Stringable
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
    #[ORM\Column(name: 'isiltasuneu', type: 'string', length: 255, nullable: true)]
    private $isiltasuneu;

    /**
     * @var string
     */
    #[ORM\Column(name: 'isiltasunes', type: 'string', length: 255, nullable: true)]
    private $isiltasunes;


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
     *          FUNTZIOAK
     */
    
    
    /**
     * @return string
     * 
     */

    public function __toString(): string
    {
        return (string) $this->getIsiltasuneu();
    }

    /**
     * Set isiltasuneu
     *
     * @param string $isiltasuneu
     *
     * @return IsiltasunAdministratiboa
     */
    public function setIsiltasuneu($isiltasuneu)
    {
        $this->isiltasuneu = $isiltasuneu;

        return $this;
    }

    /**
     * Get isiltasuneu
     *
     * @return string
     */
    public function getIsiltasuneu()
    {
        return $this->isiltasuneu;
    }

    /**
     * Set isiltasunes
     *
     * @param string $isiltasunes
     *
     * @return IsiltasunAdministratiboa
     */
    public function setIsiltasunes($isiltasunes)
    {
        $this->isiltasunes = $isiltasunes;

        return $this;
    }

    /**
     * Get isiltasunes
     *
     * @return string
     */
    public function getIsiltasunes()
    {
        return $this->isiltasunes;
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
     * @return IsiltasunAdministratiboa
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
