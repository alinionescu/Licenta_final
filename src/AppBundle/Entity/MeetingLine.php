<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MeetingLineRepository")
 * @ORM\Table(name="meeting_lines")
 * @ORM\HasLifecycleCallbacks()
 */
class MeetingLine
{
    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 0;

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="activitate", type="string", length=255, nullable=false)
     */
    protected $activitate;

    /**
     * @var \DateTime
     * @ORM\Column(name="deadline", type="datetime", nullable=false)
     */
    protected $deadline;

    /**
     * @var \DateTime
     * @ORM\Column(name="completion_date", type="datetime", nullable=false)
     */
    protected $completionDate;

    /**
     * @var boolean
     * @ORM\Column(name="signature", type="boolean", nullable=true)
     */
    protected $signature;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Person", inversedBy="meetingLines")
     * @ORM\JoinTable(name="person_meeting_line")
     */
    protected $persons;

    /**
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="person_meet", referencedColumnName="id", nullable=false)
     */
    protected $personMeet;

    /**
     * @var boolean
     * @ORM\Column(name="status", type="boolean", nullable=true)
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

    public function __construct() {
        $this->persons = new ArrayCollection();
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
     * @return MeetingLine
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getActivitate()
    {
        return $this->activitate;
    }

    /**
     * @param string $activitate
     * @return MeetingLine
     */
    public function setActivitate($activitate)
    {
        $this->activitate = $activitate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * @param \DateTime $deadline
     * @return MeetingLine
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCompletionDate()
    {
        return $this->completionDate;
    }

    /**
     * @param \DateTime $completionDate
     * @return MeetingLine
     */
    public function setCompletionDate($completionDate)
    {
        $this->completionDate = $completionDate;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isSignature()
    {
        if ($this->signature) {
            return "YES";
        }

        return "NO";
    }

    /**
     * @param boolean $signature
     * @return MeetingLine
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;
        return $this;
    }

    /**
     * @return Person
     */
    public function getPersonMeet()
    {
        return $this->personMeet;
    }

    /**
     * @param Person $personMeet
     * @return MeetingLine
     */
    public function setPersonMeet($personMeet)
    {
        $this->personMeet = $personMeet;
        return $this;
    }

    /**
     * @return Person
     */
    public function getPersons()
    {
        return $this->persons->toArray();
    }

    /**
     * @param Person $person
     * @return MeetingLine
     */
    public function setPersons(Person $person)
    {
        $this->persons[] = $person;
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
     * @return MeetingLine
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
     * @return MeetingLine
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
     * @return MeetingLine
     */
    public function setModified($modified)
    {
        $this->modified = $modified;
        return $this;
    }

    /**
     * Add person
     *
     * @param Person $person
     * @return MeetingLine
     */
    public function addMeetingLine(Person $person)
    {
        $this->persons[] = $person;

        return $this;
    }

    /**
     * Remove person
     *
     * @param Person $person
     */
    public function removeMeetingLine(Person $person)
    {
        $this->persons->removeElement($person);
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
}