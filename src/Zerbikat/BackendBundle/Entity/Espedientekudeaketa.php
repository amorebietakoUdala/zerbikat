<?php

namespace Zerbikat\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Espedientekudeaketa
 *
 * @ORM\Table(name="espedientekudeaketa")
 * @ORM\Entity
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
     * @ORM\Column(name="izenaeu", type="string", length=255)
     */
    private $izenaeu;

    /**
     * @var string
     *
     * @ORM\Column(name="izenaes", type="string", length=255)
     */
    private $izenaes;

    

    public function __toString()
    {
        return $this->getIzenaeu();
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
}