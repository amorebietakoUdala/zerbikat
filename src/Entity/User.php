<?php
// src/Zerbikat/BackendBundle/Entity/User.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use App\Annotation\UdalaEgiaztatu;
use App\Repository\UserRepository;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="fos_user")
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
     *          ERLAZIOAK
     */

    /**
     * @var udala
     * @ORM\ManyToOne(targetEntity="Udala")
     * @ORM\JoinColumn(name="udala_id", referencedColumnName="id",onDelete="SET NULL")
     *
     */
    private $udala;

    /**
     * @var \App\Entity\Azpisaila
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Azpisaila")
     * @ORM\JoinColumn(name="azpisaila_id", referencedColumnName="id",onDelete="SET NULL")
     *
     */
    private $azpisaila;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     */
    protected $password;


    /**
     *      FUNTZIOAK
     */

    /**
     *      Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->fitxaAldaketa = new ArrayCollection();
        // your own logic
    }

    /**
     * Set udala
     *
     * @param \App\Entity\Udala $udala
     *
     * @return User
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

    /**
     * Set azpisaila
     *
     * @param \App\Entity\Azpisaila $azpisaila
     *
     * @return User
     */
    public function setAzpisaila(\App\Entity\Azpisaila $azpisaila = null)
    {
        $this->azpisaila = $azpisaila;

        return $this;
    }

    /**
     * Get azpisaila
     *
     * @return \App\Entity\Azpisaila
     */
    public function getAzpisaila()
    {
        return $this->azpisaila;
    }



}
