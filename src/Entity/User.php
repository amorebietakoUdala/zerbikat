<?php
// src/Zerbikat/BackendBundle/Entity/User.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use AMREU\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use App\Annotation\UdalaEgiaztatu;
use App\Repository\UserRepository;
use App\Entity\Udala;
use App\Entity\Azpisaila;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="user")
 * @UdalaEgiaztatu(userFieldName="udala_id")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    protected $username;

    /**
     * @ORM\Column(type="json")
     */
    protected $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $email;

    /**
     * @ORM\Column(type="boolean", options={"default":"1"}, nullable=false)
     */
    protected $activated = true;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
    *          ERLAZIOAK
    */

    /**
     * @var Azpisaila
     *
     * @ORM\ManyToOne(targetEntity="Azpisaila")
     * @ORM\JoinColumn(name="azpisaila_id", referencedColumnName="id",onDelete="SET NULL")
     *
     */
    private $azpisaila;

    /**
     * @var Udala
     * @ORM\ManyToOne(targetEntity="Udala")
     * @ORM\JoinColumn(name="udala_id", referencedColumnName="id",onDelete="SET NULL")
     *
     */
    private $udala;

    /**
     *      FUNTZIOAK
     */

     /**
     * Set udala
     *
     * @param Udala $udala
     *
     * @return User
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
     * Set azpisaila
     *
     * @param Azpisaila $azpisaila
     *
     * @return User
     */
    public function setAzpisaila(Azpisaila $azpisaila = null)
    {
        $this->azpisaila = $azpisaila;

        return $this;
    }

    /**
     * Get azpisaila
     *
     * @return Azpisaila
     */
    public function getAzpisaila()
    {
        return $this->azpisaila;
    }



}
