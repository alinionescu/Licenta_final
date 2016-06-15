<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Document;
use AppBundle\Entity\Person;
use AppBundle\Entity\User;
use AppBundle\Form\DocumentType;
use AppBundle\Repository\DocumentRepository;
use AppBundle\Service\DocumentService;
use AppBundle\Service\SecurityService;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('::index.html.twig');
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->render(':admin:accessDenied.html.twig');
        }

        return $this->render(':admin:index.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listDocumentAction()
    {
        /** @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $typeId = $user->getPerson()->getPersonType()->getId();

        /** @var DocumentService $documentService */
        $documentService = $this->container->get('app_document');

        $documents = $documentService->getDocuments($user->getPerson());

        return $this->render(':Document:list-document.html.twig', [
            'documents' => $documents,
            'type' => $typeId
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editDocumentAction(Request $request)
    {
        /** @var SecurityService $securityService */
        $securityService = $this->container->get('app_security');

        if (!$securityService->isProfesor()) {
            return $this->render(':admin:accessDenied.html.twig');
        }

        $documentId = intval($request->get('id'));

        /** @var DocumentRepository $documentRepository */
        $documentRepository = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:Document');

        $document = $documentRepository->findOneBy(array('id' => $documentId));

        $form = $this->createForm(DocumentType::class, $document);

        if (null === $document) {
            $this->get('session')->getFlashBag()->add('error', 'No Document Found');

            $documents = $documentRepository->findAll();

            /** @var User $user */
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $typeId = $user->getPerson()->getPersonType()->getId();

            return $this->render(':Document:list-document.html.twig', [
                'documents' => $documents,
                'form' => $form->createView(),
                'type' => $typeId
            ]);
        }

        $form->setData($document);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $entityManager = $this->container->get('doctrine')->getManager();

            $document = $form->getData();

            $entityManager->persist($document);
            $entityManager->flush();

            $this->get('session')->getFlashBag()->add('success', 'Tema de licenta editata cu success');

            $url = $this->generateUrl('app_list_document');

            return new RedirectResponse($url);
        }

        return $this->render(':Document:edit-document.html.twig', [
            'document' => $document,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addDocumentAction(Request $request)
    {
        /** @var SecurityService $securityService */
        $securityService = $this->container->get('app_security');

        if (!$securityService->isProfesor()) {
            return $this->render(':admin:accessDenied.html.twig');
        }

        /** @var Document $document */
        $document = new Document();

        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $document = $form->getData();

            /** @var User $user */
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            /** @var Person $person */
            $person = $user->getPerson();

            $document->setPersons($person);

            $entityManager = $this->container->get('doctrine')->getManager();

            $entityManager->persist($document);
            $entityManager->flush();

            $this->get('session')->getFlashBag()->add('success', 'Tema de licenta salvata cu success');

            $url = $this->generateUrl('app_list_document');
            return new RedirectResponse($url);
        }

        return $this->render(':Document:add-document.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteDocumentAction(Request $request)
    {
        /** @var SecurityService $securityService */
        $securityService = $this->container->get('app_security');

        if (!$securityService->isProfesor()) {
            return $this->render(':admin:accessDenied.html.twig');
        }

        $documentId = intval($request->get('id'));

        /** @var DocumentRepository $documentRepository */
        $documentRepository = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:Document');

        /** @var Document $document */
        $document = $documentRepository->findOneBy(array('id' => $documentId));

        $document->setStatus(Document::STATUS_DISABLE);

        $entityManager = $this->container->get('doctrine')->getManager();
        $entityManager->persist($document);
        $entityManager->flush();

        /** @var DocumentService $documentService */
        $documentService = $this->container->get('app_document');

        /** @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $typeId = $user->getPerson()->getPersonType()->getId();

        $documents = $documentService->getDocuments($user->getPerson());

        return $this->render(':Document:list-document.html.twig', [
            'documents' => $documents,
            'type' => $typeId
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function activeDocumentAction(Request $request)
    {
        /** @var SecurityService $securityService */
        $securityService = $this->container->get('app_security');

        if (!$securityService->isProfesor()) {
            return $this->render(':admin:accessDenied.html.twig');
        }

        $documentId = intval($request->get('id'));

        /** @var DocumentRepository $documentRepository */
        $documentRepository = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:Document');

        /** @var Document $document */
        $document = $documentRepository->findOneBy(array('id' => $documentId));

        $document->setStatus(Document::STATUS_ENABLE);

        $entityManager = $this->container->get('doctrine')->getManager();
        $entityManager->persist($document);
        $entityManager->flush();

        /** @var DocumentService $documentService */
        $documentService = $this->container->get('app_document');

        /** @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $typeId = $user->getPerson()->getPersonType()->getId();

        $documents = $documentService->getDocuments($user->getPerson());

        return $this->render(':Document:list-document.html.twig', [
            'documents' => $documents,
            'type' => $typeId
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listDocumentsForStudentAction(Request $request)
    {
        /** @var DocumentService $documentService */
        $documentService = $this->container->get('app_document');

        /** @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $typeId = $user->getPerson()->getPersonType()->getId();

        if ($user->getPerson()->getDocument() == null) {
            $documents = $documentService->getAllDocuments();
            $hasDocument = false;
        } else {
            $hasDocument = true;
            $documents = [$user->getPerson()->getDocument()];
            $this->get('session')->getFlashBag()->add('success', 'Tema de licenta a fost aleasa');
        }

        return $this->render(':Document:list-document.html.twig', [
            'documents' => $documents,
            'type' => $typeId,
            'hasDocument' => $hasDocument
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function addDocumentStudentAction(Request $request)
    {
        $documentId = intval($request->get('id'));

        /** @var DocumentRepository $documentRepository */
        $documentRepository = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:Document');

        /** @var Document $document */
        $document = $documentRepository->findOneBy(array('id' => $documentId));
        $document->setIsTaken(Document::STATUS_ENABLE);

        /** @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        /** @var Person $person */
        $person = $user->getPerson();
        $person->setDocument($document);

        $entityManager = $this->container->get('doctrine')->getManager();

        $entityManager->persist($document);
        $entityManager->persist($person);
        $entityManager->flush();

        $url = $this->generateUrl('app_list_document_student');
        return new RedirectResponse($url);
    }
}