<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EspedientekudeaketaRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Espedientekudeaketa
 *
 * @ORM\Table(name="espedientekudeaketa")
 * @ORM\Entity(repositoryClass=EspedientekudeaketaRepository::class)
 */
class Espedientekudeaketa
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */

    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="izenaeu", type="string", length=255, nullable=true)
     */
    private $izenaeu;

    /**
     * @var string
     *
     * @ORM\Column(name="izenaes", type="string", length=255, nullable=true)
     */
    private $izenaes;


    /**
     * @var string
     *
     * @ORM\Column(name="urleu", type="string", length=255, nullable=true)
     */
    private $urleu;


    /**
     * @var string
     *
     * @ORM\Column(name="urles", type="string", length=255, nullable=true)
     */
    private $urles;


    /**
     *          TOSTRING
     */
    public function __toString()
    {
        return (string) $this->getIzenaeu();
    }



    /**
     *          FUNTZIOAK
     */


    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Zerbitzua",cascade={"remove"},mappedBy="espedientekudeaketa")
     */
    // private $zerbitzuak;


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
     * Set izenaeu
     *
     * @param string $izenaeu
     *
     * @return Espedientekudeaketa
     */
    public function setIzenaeu($izenaeu)
    {
        $this->izenaeu = $izenaeu;

        return $this;
    }

    /**
     * Get izenaeu
     *
     * @return string
     */
    public function getIzenaeu()
    {
        return $this->izenaeu;
    }

    /**
     * Set izenaes
     *
     * @param string $izenaes
     *
     * @return Espedientekudeaketa
     */
    public function setIzenaes($izenaes)
    {
        $this->izenaes = $izenaes;

        return $this;
    }

    /**
     * Get izenaes
     *
     * @return string
     */
    public function getIzenaes()
    {
        return $this->izenaes;
    }

    /**
     * Set urleu
     *
     * @param string $urleu
     *
     * @return Espedientekudeaketa
     */
    public function setUrleu($urleu)
    {
        $this->urleu = $urleu;

        return $this;
    }

    /**
     * Get urleu
     *
     * @return string
     */
    public function getUrleu()
    {
        return $this->urleu;
    }

    /**
     * Set urles
     *
     * @param string $urles
     *
     * @return Espedientekudeaketa
     */
    public function setUrles($urles)
    {
        $this->urles = $urles;

        return $this;
    }

    /**
     * Get urles
     *
     * @return string
     */
    public function getUrles()
    {
        return $this->urles;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
//        $this->zerbitzuak = new ArrayCollection();
    }

    // /**
    //  * Add zerbitzuak
    //  *
    //  * @param UdalaZerbitzua $zerbitzuak
    //  *
    //  * @return Espedientekudeaketa
    //  */
    // public function addZerbitzuak(UdalaZerbitzua $zerbitzuak)
    // {
    //     $this->zerbitzuak[] = $zerbitzuak;

    //     return $this;
    // }

    // /**
    //  * Remove zerbitzuak
    //  *
    //  * @param UdalaZerbitzua $zerbitzuak
    //  */
    // public function removeZerbitzuak(UdalaZerbitzua $zerbitzuak)
    // {
    //     $this->zerbitzuak->removeElement($zerbitzuak);
    // }

    // /**
    //  * Get zerbitzuak
    //  *
    //  * @return ArrayCollection
    //  */
    // public function getZerbitzuak()
    // {
    //     return $this->zerbitzuak;
    // }
}
