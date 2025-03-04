<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ZerbitzuaRepository;

/**
 * Zerbitzua
 */
#[ORM\Table(name: 'zerbitzua')]
#[ORM\Entity(repositoryClass: ZerbitzuaRepository::class)]
class Zerbitzua implements \Stringable
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
    #[ORM\Column(name: 'kodea', type: 'string', length: 10, nullable: false)]
    private $kodea;

    /**
     * @var string
     */
    #[ORM\Column(name: 'zerbitzuaeu', type: 'string', length: 255, nullable: true)]
    private $zerbitzuaeu;

    /**
     * @var string
     */
    #[ORM\Column(name: 'zerbitzuaes', type: 'string', length: 255, nullable: true)]
    private $zerbitzuaes;


    /**
     * @var string
     */
    #[ORM\Column(name: 'erroaeu', type: 'string', length: 255, nullable: true)]
    private $erroaeu;

    /**
     * @var string
     */
    #[ORM\Column(name: 'erroaes', type: 'string', length: 255, nullable: true)]
    private $erroaes;



    /**
     *      ERLAZIOAK
     */

    /**
     *          TOSTRING
     */
    public function __toString(): string
    {
        return $this->getKodea()."-".$this->getZerbitzuaeu();
    }

    /**
     *          FUNTZIOAK
     */

    /**
     * Set kodea
     *
     * @param string $kodea
     * @return Zerbitzua
     */
    public function setKodea($kodea)
    {
        $this->kodea = $kodea;

        return $this;
    }

    /**
     * Get kodea
     *
     * @return string 
     */
    public function getKodea()
    {
        return $this->kodea;
    }

    /**
     * Set zerbitzuaeu
     *
     * @param string $zerbitzuaeu
     * @return Zerbitzua
     */
    public function setZerbitzuaeu($zerbitzuaeu)
    {
        $this->zerbitzuaeu = $zerbitzuaeu;

        return $this;
    }

    /**
     * Get zerbitzuaeu
     *
     * @return string 
     */
    public function getZerbitzuaeu()
    {
        return $this->zerbitzuaeu;
    }

    /**
     * Set zerbitzuaes
     *
     * @param string $zerbitzuaes
     * @return Zerbitzua
     */
    public function setZerbitzuaes($zerbitzuaes)
    {
        $this->zerbitzuaes = $zerbitzuaes;

        return $this;
    }

    /**
     * Get zerbitzuaes
     *
     * @return string 
     */
    public function getZerbitzuaes()
    {
        return $this->zerbitzuaes;
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
     * Set erroaeu
     *
     * @param string $erroaeu
     *
     * @return Zerbitzua
     */
    public function setErroaeu($erroaeu)
    {
        $this->erroaeu = $erroaeu;

        return $this;
    }

    /**
     * Get erroaeu
     *
     * @return string
     */
    public function getErroaeu()
    {
        return $this->erroaeu;
    }

    /**
     * Set erroaes
     *
     * @param string $erroaes
     *
     * @return Zerbitzua
     */
    public function setErroaes($erroaes)
    {
        $this->erroaes = $erroaes;

        return $this;
    }

    /**
     * Get erroaes
     *
     * @return string
     */
    public function getErroaes()
    {
        return $this->erroaes;
    }
}
