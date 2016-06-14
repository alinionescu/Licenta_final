<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Person;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

class DocumentRepository extends EntityRepository
{
    /**
     * @param $documentId
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getDocumentById($documentId)
    {
        $query = $this->createQueryBuilder('d')
            ->select('d')
            ->where('d.id = :documentId')->setParameter('documentId', $documentId);

        try {
            $result = $query->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $ex) {
            return null;
        }

        return $result;
    }

    /**
     * @param Person $person
     * @return array
     */
    public function getDocuments(Person $person)
    {
        $query = $this->createQueryBuilder('d')
            ->select('d')
            ->leftJoin('d.persons', 'p')
            ->where('p.id = :person')->setParameter('person', $person->getId());

        $result = $query->getQuery()->getResult();

        return $result;
    }
}
