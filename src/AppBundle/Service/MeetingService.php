<?php

namespace AppBundle\Service;

use AppBundle\Entity\Person;
use AppBundle\Repository\MeetingLineRepository;
use Doctrine\ORM\EntityManager;

class MeetingService
{
    /** @var  EntityManager */
    protected $entityManager;

    /**
     * MeetingService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public function getMeetings(Person $person)
    {
        /** @var MeetingLineRepository $meetingLineRepository */
        $meetingLineRepository = $this->entityManager->getRepository('AppBundle:MeetingLine');

        $meetings = $meetingLineRepository->getMeetingLines($person);

        return $meetings;
    }

    /**
     * @param Person $person
     * @return array
     */
    public function getMeetingsStudent(Person $person)
    {
        /** @var MeetingLineRepository $meetingLineRepository */
        $meetingLineRepository = $this->entityManager->getRepository('AppBundle:MeetingLine');

        $meetings = $meetingLineRepository->getMeetingLinesStudent($person);

        return $meetings;
    }
}