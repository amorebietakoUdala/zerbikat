<?php

    namespace Zerbikat\BackendBundle\Entity;

    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\ORM\Mapping as ORM;
    use JMS\Serializer\Annotation\ExclusionPolicy;
    use JMS\Serializer\Annotation\Expose;
    use Zerbikat\BackendBundle\Annotation\UdalaEgiaztatu;

    /**
     * Familia
     *
     * @ORM\Table(name="familia")
     * @ORM\Entity
     * @ExclusionPolicy("all")
     * @UdalaEgiaztatu(userFieldName="udala_id")
     */
    class Familia
    {

        /**
         * @var integer
         * @Expose
         *
         * @ORM\Column(name="id", type="bigint")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         *
         * @var string
         * @Expose
         *
         * @ORM\Column(name="familiaeu", type="string", length=255, nullable=true)
         */
        private $familiaeu;


        /**
         * @var string
         * @Expose
         *
         * @ORM\Column(name="familiaes", type="string", length=255, nullable=true)
         */
        private $familiaes;

        /**
         * @var string
         * @Expose
         *
         * @ORM\Column(name="deskribapenaeu", type="string", length=255, nullable=true)
         */
        private $deskribapenaeu;

        /**
         * @var string
         * @Expose
         *
         * @ORM\Column(name="deskribapenaes", type="string", length=255, nullable=true)
         */
        private $deskribapenaes;

        /**
         * @var int
         * @Expose
         *
         * @ORM\Column(name="ordena", type="integer", nullable=true)
         */
        private $ordena;


        /**************************************************************************************************************
         **************************************************************************************************************
         ******************      ERLAZIOAK
         **************************************************************************************************************
         *************************************************************************************************************/

        /**
         * @var udala
         * @ORM\ManyToOne(targetEntity="Udala")
         * @ORM\JoinColumn(name="udala_id", referencedColumnName="id",onDelete="CASCADE")
         *
         */
        private $udala;

        /**
         * @ORM\OneToMany(targetEntity="Zerbikat\BackendBundle\Entity\Fitxafamilia", mappedBy="familia")
         */
        private $fitxafamilia;

        /**
         * @ORM\ManyToOne(targetEntity="Zerbikat\BackendBundle\Entity\Familia", inversedBy="children")
         */
        private $parent;

        /**
         * @ORM\OneToMany(targetEntity="Zerbikat\BackendBundle\Entity\Familia", mappedBy="parent")
         */
        private $children;

        /**************************************************************************************************************
         **************************************************************************************************************
         ******************      ERLAZIOAK FIN
         **************************************************************************************************************
         *************************************************************************************************************/


        /**
         *      FUNTZIOAK
         */

        /**
         * @return string
         */

        public function __toString ()
        {
            return $this->getFamiliaeu();
        }

        /**
         * Constructor
         */
        public function __construct ()
        {
            $this->fitxafamilia = new ArrayCollection();
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
     * Set familiaeu
     *
     * @param string $familiaeu
     *
     * @return Familia
     */
    public function setFamiliaeu($familiaeu)
    {
        $this->familiaeu = $familiaeu;

        return $this;
    }

    /**
     * Get familiaeu
     *
     * @return string
     */
    public function getFamiliaeu()
    {
        return $this->familiaeu;
    }

    /**
     * Set familiaes
     *
     * @param string $familiaes
     *
     * @return Familia
     */
    public function setFamiliaes($familiaes)
    {
        $this->familiaes = $familiaes;

        return $this;
    }

    /**
     * Get familiaes
     *
     * @return string
     */
    public function getFamiliaes()
    {
        return $this->familiaes;
    }

    /**
     * Set deskribapenaeu
     *
     * @param string $deskribapenaeu
     *
     * @return Familia
     */
    public function setDeskribapenaeu($deskribapenaeu)
    {
        $this->deskribapenaeu = $deskribapenaeu;

        return $this;
    }

    /**
     * Get deskribapenaeu
     *
     * @return string
     */
    public function getDeskribapenaeu()
    {
        return $this->deskribapenaeu;
    }

    /**
     * Set deskribapenaes
     *
     * @param string $deskribapenaes
     *
     * @return Familia
     */
    public function setDeskribapenaes($deskribapenaes)
    {
        $this->deskribapenaes = $deskribapenaes;

        return $this;
    }

    /**
     * Get deskribapenaes
     *
     * @return string
     */
    public function getDeskribapenaes()
    {
        return $this->deskribapenaes;
    }

    /**
     * Set udala
     *
     * @param \Zerbikat\BackendBundle\Entity\Udala $udala
     *
     * @return Familia
     */
    public function setUdala(\Zerbikat\BackendBundle\Entity\Udala $udala = null)
    {
        $this->udala = $udala;

        return $this;
    }

    /**
     * Get udala
     *
     * @return \Zerbikat\BackendBundle\Entity\Udala
     */
    public function getUdala()
    {
        return $this->udala;
    }

    /**
     * Add fitxafamilium
     *
     * @param \Zerbikat\BackendBundle\Entity\Fitxafamilia $fitxafamilium
     *
     * @return Familia
     */
    public function addFitxafamilium(\Zerbikat\BackendBundle\Entity\Fitxafamilia $fitxafamilium)
    {
        $this->fitxafamilia[] = $fitxafamilium;

        return $this;
    }

    /**
     * Remove fitxafamilium
     *
     * @param \Zerbikat\BackendBundle\Entity\Fitxafamilia $fitxafamilium
     */
    public function removeFitxafamilium(\Zerbikat\BackendBundle\Entity\Fitxafamilia $fitxafamilium)
    {
        $this->fitxafamilia->removeElement($fitxafamilium);
    }

    /**
     * Get fitxafamilia
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFitxafamilia()
    {
        return $this->fitxafamilia;
    }

    /**
     * Set parent
     *
     * @param \Zerbikat\BackendBundle\Entity\Familia $parent
     *
     * @return Familia
     */
    public function setParent(\Zerbikat\BackendBundle\Entity\Familia $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Zerbikat\BackendBundle\Entity\Familia
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param \Zerbikat\BackendBundle\Entity\Familia $child
     *
     * @return Familia
     */
    public function addChild(\Zerbikat\BackendBundle\Entity\Familia $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Zerbikat\BackendBundle\Entity\Familia $child
     */
    public function removeChild(\Zerbikat\BackendBundle\Entity\Familia $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set ordena
     *
     * @param integer $ordena
     *
     * @return Familia
     */
    public function setOrdena($ordena)
    {
        $this->ordena = $ordena;

        return $this;
    }

    /**
     * Get ordena
     *
     * @return integer
     */
    public function getOrdena()
    {
        return $this->ordena;
    }
}
