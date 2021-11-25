<?php

namespace Zerbikat\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fitxa_aldaketa")
 */
class FitxaAldaketa
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $fitxaId;

    /**
     * @ORM\Column(type="string")
     */
    private $fitxaKodea;

    /**
     * @ORM\Column(type="string")
     */
    private $nork;

    /**
     * @ORM\Column(type="datetime")
     */
    private $noiz;

    /**
     * @ORM\Column(type="string")
     */
    private $aldaketaMota;

    public function getId()
    {
        return $this->id;
    }


    /**
     * Get the value of noiz
     */ 
    public function getNoiz()
    {
        return $this->noiz;
    }

    /**
     * Set the value of noiz
     *
     * @return  self
     */ 
    public function setNoiz(\DateTime $noiz)
    {
        $this->noiz = $noiz;

        return $this;
    }

    /**
     * Get the value of fitxaKodea
     */ 
    public function getFitxaKodea()
    {
        return $this->fitxaKodea;
    }

    /**
     * Set the value of fitxaKodea
     *
     * @return  self
     */ 
    public function setFitxaKodea($fitxaKodea)
    {
        $this->fitxaKodea = $fitxaKodea;

        return $this;
    }

    /**
     * Get the value of nork
     */ 
    public function getNork()
    {
        return $this->nork;
    }

    /**
     * Set the value of nork
     *
     * @return  self
     */ 
    public function setNork($nork)
    {
        $this->nork = $nork;

        return $this;
    }

    /**
     * Get the value of aldaketaMota
     */ 
    public function getAldaketaMota()
    {
        return $this->aldaketaMota;
    }

    /**
     * Set the value of aldaketaMota
     *
     * @return  self
     */ 
    public function setAldaketaMota($aldaketaMota)
    {
        $this->aldaketaMota = $aldaketaMota;

        return $this;
    }

    /**
     * Get the value of fitxaId
     */ 
    public function getFitxaId()
    {
        return $this->fitxaId;
    }

    /**
     * Set the value of fitxaId
     *
     * @return  self
     */ 
    public function setFitxaId($fitxaId)
    {
        $this->fitxaId = $fitxaId;

        return $this;
    }
}
