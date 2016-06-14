<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MeetingLine;
use AppBundle\Entity\Meetings;
use AppBundle\Form\MeetingLineType;
use AppBundle\Form\MeetingsType;
use AppBundle\Service\MeetingService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class MeetingController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listMeetingsAction()
    {
        /** @var MeetingService $meetingService */
        $meetingService = $this->container->get('app_meeting');

        $meetings = $meetingService->getAllMeetings();

        return $this->render(':Meeting:list-meeting.html.twig', [
            'meetings' => $meetings
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editMeetingAction(Request $request)
    {
        $documentId = intval($request->get('id'));

        /** @var DocumentRepository $documentRepository */
        $documentRepository = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:Document');

        $document = $documentRepository->findOneBy(array('id' => $documentId));

        $form = $this->createForm(DocumentType::class, $document);

        if (null === $document) {
            $this->get('session')->getFlashBag()->add('error', 'No Document Found');

            $documents = $documentRepository->findAll();

            return $this->render(':Document:list-document.html.twig', [
                'documents' => $documents,
                'form' => $form->createView()
            ]);
        }

        $form->handleRequest($request);
        $form->setData($document);

        if ($form->isValid() && $form->isSubmitted()) {
            $entityManager = $this->container->get('doctrine')->getManager();

            $document = $form->getData();

            $entityManager->persist($document);
            $entityManager->flush();

            $this->get('session')->getFlashBag()->add('success', 'Document salvat cu success');

            return $this->render(':Document:add-document.html.twig', [
                'form' => $form->createView()
            ]);

            $url = $this->generateUrl('app_list_document');

            return new RedirectResponse($url);
        }

        return $this->render(':Document:edit-document.html.twig', [
            'documents' => [],
            'document' => $document,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addMeetingAction(Request $request)
    {
        $meetingLine = new MeetingLine();
        $form = $this->createForm(MeetingLineType::class, $meetingLine);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $entityManager = $this->container->get('doctrine')->getManager();

            $meetingLine = $form->getData();

            $entityManager->persist($meetingLine);
            $entityManager->flush();

            $this->get('session')->getFlashBag()->add('success', 'Intalnire salvata cu success');

            $url = $this->generateUrl('app_list_meeting');
            return new RedirectResponse($url);
        }

        return $this->render(':Meeting:add-meeting.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteMeetingAction(Request $request)
    {
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

        $documents = $documentService->getAllDocuments();

        return $this->render(':Document:list-document.html.twig', [
            'documents' => $documents
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function activeMeetingAction(Request $request)
    {
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

        $documents = $documentService->getAllDocuments();

        return $this->render(':Document:list-document.html.twig', [
            'documents' => $documents
        ]);
    }
}