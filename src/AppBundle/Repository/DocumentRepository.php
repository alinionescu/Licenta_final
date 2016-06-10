<?php

namespace AppBundle\Repository;

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
}
