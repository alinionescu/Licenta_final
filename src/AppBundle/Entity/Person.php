<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Document;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PersonRepository")
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
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\MeetingLine", inversedBy="persons")
     */
    protected $meetingLines;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Document", inversedBy="persons")
     * @ORM\JoinTable(name="person_document",
     *   joinColumns={
     *     @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="document_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $documents;

    public function __construct() {
        $this->meetingLines = new ArrayCollection();
    }

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
     * @return mixed
     */
    public function getMeetingLine()
    {
        return $this->meetingLines->toArray();
    }

    /**
     * @param mixed $meetingLine
     * @return Person
     */
    public function setMeetingLine($meetingLine)
    {
        $this->meetingLine = $meetingLine;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * @param ArrayCollection $documents
     * @return Person
     */
    public function setDocuments($documents)
    {
        $this->documents = $documents;
        return $this;
    }

    /**
     * Add meetingLine
     *
     * @param MeetingLine $meetingLine
     * @return Person
     */
    public function addMeetingLine(MeetingLine $meetingLine)
    {
        $this->meetingLines[] = $meetingLine;

        return $this;
    }

    /**
     * Remove meetingLine
     *
     * @param MeetingLine $meetingLine
     */
    public function removeMeetingLine(MeetingLine $meetingLine)
    {
        $this->meetingLines->removeElement($meetingLine);
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if (!$this->getCreated()) {
            $this->created = new \DateTime();
        }

        if ($this->getModified() === null) {
            $this->modified = new \DateTime();
        }
    }

    public function getName()
    {
        return $this->firstName . " " .$this->lastName;
    }
}