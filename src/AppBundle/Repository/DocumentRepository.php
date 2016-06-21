<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Document;
use AppBundle\Entity\Person;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

class DocumentRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getDocuments()
    {
        $query = $this->createQueryBuilder('d')
            ->select('d')
            ->where('d.isTaken = :isTaken')->setParameter('isTaken', Document::STATUS_DISABLE)
            ->andWhere('d.status = :status')->setParameter('status', Document::STATUS_ENABLE);

        $result = $query->getQuery()->getResult();

        return $result;
    }
}
