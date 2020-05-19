<?php

namespace App\Controller;

use App\Entity\Lesson;
use App\Entity\Presence;
use App\Repository\PresenceRepository;
use App\Service\LessonService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/lesson")
 */
class LessonController extends AbstractController
{

    public function __construct(PresenceRepository $presenceRepository, LessonService $lessonService)
    {
        $this->presenceRepository = $presenceRepository;
        $this->lessonService = $lessonService;

    }

    /**
     * @Route("/", name="lesson.index")
     */
    public function index()
    {
        return $this->render('lesson/index.html.twig', [
            'lessons' => $this->lessonService->getLessonsForOneUser(),
        ]);

    }

    /**
     * @Route("/{id}/show", name="lesson.show")
     */
    public function show(Lesson $lesson, UserInterface $user)
    {
        $presence = $this->presenceRepository->findOneByUserAndLesson($user, $lesson);

        return $this->render('lesson/show.html.twig', [
            'lesson' => $lesson,
            'presence' => $presence,
        ]);
    }

    /**
     * @Route("/{id}/answer/{answer}/{from}", name="lesson.answer")
     */
    public function answer(Lesson $lesson, $answer, $from = "index", UserInterface $user)
    {
        // Je vais chercher une Présence pour ce cours et cet utilisateur
        $presence = $this->presenceRepository->findOneByUserAndLesson($user, $lesson);
        // Si l'utilisateur n'a pas déjà répondu à ce cours
        if (!$presence) {
            // Je crée une nouvelle Présence
            $presence = new Presence();
            $presence->setLesson($lesson);
            $presence->setUser($user);
        }
        // Dans tous les cas, je mets à jour la réponse
        $presence->setStatus($answer);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($presence);
        $entityManager->flush();

        // Si je réponds depuis l'index
        if ($from == "index") {
            return $this->redirectToRoute('lesson.index');
        }
        // Si je réponds depuis le détail de la Lesson
        else {
            return $this->redirectToRoute('lesson.show', [
                'id' => $lesson->getId(),
            ]);
        }
    }
}
