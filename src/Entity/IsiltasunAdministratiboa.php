<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Annotation\UdalaEgiaztatu;
use App\Repository\IsiltasunAdministratiboaRepository;

/**
 * IsiltasunAdministratiboa
 *
 * @ORM\Table(name="isiltasunadministratiboa")
 * @ORM\Entity(repositoryClass=IsiltasunAdministratiboaRepository::class)
 * @UdalaEgiaztatu(userFieldName="udala_id")
 */
class IsiltasunAdministratiboa
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
     * @ORM\Column(name="isiltasuneu", type="string", length=255, nullable=true)
     */
    private $isiltasuneu;

    /**
     * @var string
     *
     * @ORM\Column(name="isiltasunes", type="string", length=255, nullable=true)
     */
    private $isiltasunes;


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
    
    
    /**
     * @return string
     * 
     */

    public function __toString()
    {
        return (string) $this->getIsiltasuneu();
    }
    /**
     * Constructor
     */
    public function __construct()
    {
//        $this->fitxak = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add fitxak
     *
     * @param \App\Entity\Fitxa $fitxak
     *
     * @return IsiltasunAdministratiboa
     */
    public function addFitxak(\App\Entity\Fitxa $fitxak)
    {
        $this->fitxak[] = $fitxak;

        return $this;
    }

    /**
     * Remove fitxak
     *
     * @param \App\Entity\Fitxa $fitxak
     */
    public function removeFitxak(\App\Entity\Fitxa $fitxak)
    {
        $this->fitxak->removeElement($fitxak);
    }

    /**
     * Get fitxak
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFitxak()
    {
        return $this->fitxak;
    }

    /**
     * Set udala
     *
     * @param \App\Entity\Udala $udala
     *
     * @return IsiltasunAdministratiboa
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
