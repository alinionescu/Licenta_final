<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="person")
 * @ORM\HasLifecycleCallbacks()
 */
class Person
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var User
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", inversedBy="person", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var integer
     * @ORM\Column(name="id_matricol", type="integer", nullable=true)
     */
    protected $matricol;

    /**
     * @var Document
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Document", inversedBy="person", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="id_document", referencedColumnName="id")
     */
    protected $document;

    /**
     * @var PersonType
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PersonType", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="person_type_id", referencedColumnName="id")
     */
    protected $personType;

    /**
     * @var string
     * @ORM\Column(name="first_name", type="string", length=64, nullable=false)
     */
    protected $firstName;

    /**
     * @var string
     * @ORM\Column(name="last_name", type="string", length=64, nullable=false)
     */
    protected $lastName;

    /**
     * @var string
     * @ORM\Column(name="phone", type="string", length=64, nullable=true)
     */
    protected $phone;

    /**
     * @var string
     * @ORM\Column(name="cnp", type="string", length=64, nullable=true)
     */
    protected $cnp;

    /**
     * @var bool
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    protected $status;

    /**
     * @var \DateTime
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    protected $created;

    /**
     * @var \DateTime
     * @ORM\Column(name="modified", type="datetime", nullable=true)
     */
    protected $modified;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Meetings", inversedBy="persons")
     */
    protected $meetings;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Document", inversedBy="persons")
     */
    protected $documents;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Person
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Person
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return int
     */
    public function getMatricol()
    {
        return $this->matricol;
    }

    /**
     * @param int $matricol
     * @return Person
     */
    public function setMatricol($matricol)
    {
        $this->matricol = $matricol;
        return $this;
    }

    /**
     * @return Document
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param Document $document
     * @return Person
     */
    public function setDocument($document)
    {
        $this->document = $document;
        return $this;
    }

    /**
     * @return PersonType
     */
    public function getPersonType()
    {
        return $this->personType;
    }

    /**
     * @param PersonType $personType
     * @return Person
     */
    public function setPersonType($personType)
    {
        $this->personType = $personType;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return Person
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return Person
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return Person
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getCnp()
    {
        return $this->cnp;
    }

    /**
     * @param string $cnp
     * @return Person
     */
    public function setCnp($cnp)
    {
        $this->cnp = $cnp;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isStatus()
    {
        return $this->status;
    }

    /**
     * @param boolean $status
     * @return Person
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     * @return Person
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @param \DateTime $modified
     * @return Person
     */
    public function setModified($modified)
    {
        $this->modified = $modified;
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if (!$this->getCreated()) {
            $this->created = new \DateTime();
        }
    }
}