<?php

    namespace App\Entity;

    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\ORM\Mapping as ORM;
    use JMS\Serializer\Annotation\ExclusionPolicy;
    use JMS\Serializer\Annotation\Expose;
    use App\Attribute\UdalaEgiaztatu;
    use App\Repository\FamiliaRepository;

    #[ExclusionPolicy("all")]
    #[UdalaEgiaztatu(userFieldName: "udala_id")]
    #[ORM\Table(name: 'familia')]
    #[ORM\Entity(repositoryClass: FamiliaRepository::class)]
    class Familia implements \Stringable
    {

        /**
         * @var integer
         */
        #[Expose()]
        #[ORM\Column(name: 'id', type: 'bigint')]
        #[ORM\Id]
        #[ORM\GeneratedValue(strategy: 'IDENTITY')]
        private $id;

        /**
         *
         * @var string
         */
        #[Expose()]
        #[ORM\Column(name: 'familiaeu', type: 'string', length: 255, nullable: true)]
        private $familiaeu;


        /**
         * @var string
         */
        #[Expose()]
        #[ORM\Column(name: 'familiaes', type: 'string', length: 255, nullable: true)]
        private $familiaes;

        /**
         * @var string
         */
        #[Expose()]
        #[ORM\Column(name: 'deskribapenaeu', type: 'string', length: 255, nullable: true)]
        private $deskribapenaeu;

        /**
         * @var string
         */
        #[Expose()]
        #[ORM\Column(name: 'deskribapenaes', type: 'string', length: 255, nullable: true)]
        private $deskribapenaes;

        /**
         * @var int
         */
        #[Expose()]
        #[ORM\Column(name: 'ordena', type: 'integer', nullable: true)]
        private $ordena=0;


        /**************************************************************************************************************
         **************************************************************************************************************
         ******************      ERLAZIOAK
         **************************************************************************************************************
         *************************************************************************************************************/
        /**
         * @var Udala
         *
         */
        #[ORM\JoinColumn(name: 'udala_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
        #[ORM\ManyToOne(targetEntity: Udala::class)]
        private $udala;

        /**
         * @var ArrayCollection
         */
        #[ORM\OneToMany(targetEntity: Fitxafamilia::class, mappedBy: 'familia')]
        #[ORM\OrderBy(['ordena' => 'ASC'])]
        private $fitxafamilia;

        #[ORM\ManyToOne(targetEntity: Familia::class, inversedBy: 'children')]
        private $parent;

        #[ORM\OneToMany(targetEntity: Familia::class, mappedBy: 'parent')]
        #[ORM\OrderBy(['ordena' => 'ASC'])]
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

        public function __toString (): string
        {
            return (string) $this->getFamiliaeu();
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
     * @param Udala $udala
     *
     * @return Familia
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
     * Add fitxafamilium
     *
     * @param Fitxafamilia $fitxafamilium
     *
     * @return Familia
     */
    public function addFitxafamilium(Fitxafamilia $fitxafamilium)
    {
        $this->fitxafamilia[] = $fitxafamilium;

        return $this;
    }

    /**
     * Remove fitxafamilium
     *
     * @param Fitxafamilia $fitxafamilium
     */
    public function removeFitxafamilium(Fitxafamilia $fitxafamilium)
    {
        $this->fitxafamilia->removeElement($fitxafamilium);
    }

    /**
     * Get fitxafamilia
     *
     * @return Collection
     */
    public function getFitxafamilia()
    {
        return $this->fitxafamilia;
    }

    /**
     * Set parent
     *
     * @param Familia $parent
     *
     * @return Familia
     */
    public function setParent(Familia $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return Familia
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param Familia $child
     *
     * @return Familia
     */
    public function addChild(Familia $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param Familia $child
     */
    public function removeChild(Familia $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return Collection
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
