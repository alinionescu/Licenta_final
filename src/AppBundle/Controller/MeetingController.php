<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MeetingLine;
use AppBundle\Entity\Meetings;
use AppBundle\Entity\Person;
use AppBundle\Entity\PersonType;
use AppBundle\Entity\User;
use AppBundle\Form\MeetingLineType;
use AppBundle\Form\MeetingsType;
use AppBundle\Repository\MeetingLineRepository;
use AppBundle\Service\MeetingService;
use AppBundle\Service\SecurityService;
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

        /** @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        if ($user->getPerson()->getPersonType()->getId() !== PersonType::PERSON_TYPE_STUDENT) {
            $meetings = $meetingService->getMeetings($user->getPerson());
        } else {
            $meetings = $meetingService->getMeetingsStudent($user->getPerson());
        }

        return $this->render(':Meeting:list-meeting.html.twig', [
            'meetings' => $meetings,
            'type' => $user->getPerson()->getPersonType()->getId()
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editMeetingAction(Request $request)
    {
        /** @var SecurityService $securityService */
        $securityService = $this->container->get('app_security');

        if (!$securityService->isProfesor()) {
            return $this->render(':admin:accessDenied.html.twig');
        }

        $meetingLineId = intval($request->get('id'));

        /** @var MeetingLineRepository $meetingLineRepository */
        $meetingLineRepository = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:MeetingLine');

        $meetingLine = $meetingLineRepository->findOneBy(array('id' => $meetingLineId));

        $form = $this->createForm(MeetingLineType::class, $meetingLine);

        if (null === $meetingLine) {
            $this->get('session')->getFlashBag()->add('error', 'No MeetingLine Found');

            $meetingLines = $meetingLineRepository->findAll();

            /** @var User $user */
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            return $this->render(':Meeting:list-meeting.html.twig', [
                'meetings' => $meetingLines,
                'form' => $form->createView(),
                'type' => $user->getPerson()->getPersonType()->getId()
            ]);
        }

        $form->handleRequest($request);
        $form->setData($meetingLine);

        if ($form->isValid() && $form->isSubmitted()) {
            $entityManager = $this->container->get('doctrine')->getManager();

            $meetingLine = $form->getData();

            $entityManager->persist($meetingLine);
            $entityManager->flush();

            $this->get('session')->getFlashBag()->add('success', 'Intalnire editata cu success');

            $url = $this->generateUrl('app_list_meeting');

            return new RedirectResponse($url);
        }

        return $this->render(':Meeting:edit-meeting.html.twig', [
            'meeting' => $meetingLine,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addMeetingAction(Request $request)
    {
        /** @var SecurityService $securityService */
        $securityService = $this->container->get('app_security');

        if (!$securityService->isProfesor()) {
            return $this->render(':admin:accessDenied.html.twig');
        }

        /** @var MeetingLine $meetingLine */
        $meetingLine = new MeetingLine();

        $form = $this->createForm(MeetingLineType::class, $meetingLine);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $meetingLine = $form->getData();

            /** @var User $user */
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $person = $user->getPerson();
            $meetingLine->setPersons($person);

            $entityManager = $this->container->get('doctrine')->getManager();
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
        /** @var SecurityService $securityService */
        $securityService = $this->container->get('app_security');

        if (!$securityService->isProfesor()) {
            return $this->render(':admin:accessDenied.html.twig');
        }

        $meetingLineId = intval($request->get('id'));

        /** @var MeetingLineRepository $meetingLineRepository */
        $meetingLineRepository = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:MeetingLine');

        /** @var MeetingLine $meetingLine */
        $meetingLine = $meetingLineRepository->findOneBy(array('id' => $meetingLineId));

        $meetingLine->setStatus(MeetingLine::STATUS_DISABLE);

        $entityManager = $this->container->get('doctrine')->getManager();
        $entityManager->persist($meetingLine);
        $entityManager->flush();

        /** @var MeetingService $meetingService */
        $meetingService = $this->container->get('app_meeting');

        /** @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $meetings = $meetingService->getMeetings($user->getPerson());

        /** @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        return $this->render(':Meeting:list-meeting.html.twig', [
            'meetings' => $meetings,
            'type' => $user->getPerson()->getPersonType()->getId()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function activeMeetingAction(Request $request)
    {
        /** @var SecurityService $securityService */
        $securityService = $this->container->get('app_security');

        if (!$securityService->isProfesor()) {
            return $this->render(':admin:accessDenied.html.twig');
        }

        $meetingLineId = intval($request->get('id'));

        /** @var MeetingLineRepository $meetingLineRepository */
        $meetingLineRepository = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:MeetingLine');

        /** @var MeetingLine $meetingLine */
        $meetingLine = $meetingLineRepository->findOneBy(array('id' => $meetingLineId));

        $meetingLine->setStatus(MeetingLine::STATUS_ENABLE);

        $entityManager = $this->container->get('doctrine')->getManager();
        $entityManager->persist($meetingLine);
        $entityManager->flush();

        /** @var MeetingService $meetingService */
        $meetingService = $this->container->get('app_meeting');

        /** @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $meetings = $meetingService->getMeetings($user->getPerson());

        /** @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        return $this->render(':Meeting:list-meeting.html.twig', [
            'meetings' => $meetings,
            'type' => $user->getPerson()->getPersonType()->getId()
        ]);
    }
}