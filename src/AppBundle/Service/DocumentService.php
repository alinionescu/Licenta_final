<?php

namespace AppBundle\Service;

use AppBundle\Entity\Document;
use AppBundle\Entity\Person;
use AppBundle\Repository\DocumentRepository;
use Doctrine\ORM\EntityManager;

class DocumentService
{
    /** @var  EntityManager */
    protected $entityManager;

    /**
     * DocumentService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public function getAllDocuments()
    {
        /** @var DocumentRepository $documentRepository */
        $documentRepository = $this->entityManager->getRepository('AppBundle:Document');

        /** @var Document[]|Document $documents */
        $documents = $documentRepository->getDocuments();

        return $documents;
    }
}
