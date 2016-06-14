<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Person;
use Doctrine\ORM\EntityRepository;

class MeetingLineRepository extends EntityRepository
{
    /**
     * @param Person $person
     * @return array
     */
    public function getMeetingLines(Person $person)
    {
        $query = $this->createQueryBuilder('ml')
            ->select('ml')
            ->leftJoin('ml.persons', 'p')
            ->where('p.id = :person')->setParameter('person', $person->getId());

        $result = $query->getQuery()->getResult();

        return $result;
    }

    /**
     * @param Person $person
     * @return array
     */
    public function getMeetingLinesStudent(Person $person)
    {
        $query = $this->createQueryBuilder('ml')
            ->select('ml')
            ->leftJoin('ml.personMeet', 'p')
            ->where('p.id = :person')->setParameter('person', $person->getId());

        $result = $query->getQuery()->getResult();

        return $result;
    }
}