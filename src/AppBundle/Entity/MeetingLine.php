<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="meeting_lines")
 * @ORM\HasLifecycleCallbacks()
 */
class MeetingLine
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_meet", type="datetime", nullable=true)
     */
    protected $dateMeet;

    /**
     * @var Meetings
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Meetings", inversedBy="meetingLine", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="meeting", referencedColumnName="id")
     */
    protected $meeting;

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
     * @return \DateTime
     */
    public function getDateMeet()
    {
        return $this->dateMeet;
    }

    /**
     * @param \DateTime $dateMeet
     * @return MeetingLine
     */
    public function setDateMeet($dateMeet)
    {
        $this->dateMeet = $dateMeet;
        return $this;
    }

    /**
     * @return Meetings
     */
    public function getMeetings()
    {
        return $this->meetings;
    }

    /**
     * @param Meetings $meetings
     * @return MeetingLine
     */
    public function setMeetings($meetings)
    {
        $this->meetings = $meetings;
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
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if (!$this->getCreated()) {
            $this->created = new \DateTime();
        }
    }
}