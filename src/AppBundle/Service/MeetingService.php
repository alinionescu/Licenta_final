<?php

namespace AppBundle\Service;

use AppBundle\Repository\MeetingsRepository;
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
    public function getAllMeetings()
    {
        /** @var MeetingsRepository $meetingsRepository */
        $meetingsRepository = $this->entityManager->getRepository('AppBundle:MeetingLine');

        $meetings = $meetingsRepository->findAll();

        return $meetings;
    }
}