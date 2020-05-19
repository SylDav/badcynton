<?php

namespace App\Controller\Admin;

use App\Entity\Lesson;
use App\Entity\Presence;
use App\Entity\LessonSearch;
use App\Form\LessonType;
use App\Form\LessonSearchType;
use App\Repository\LessonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/lesson")
 */
class AdminLessonController extends AbstractController
{
    /**
     * @Route("/", name="admin.lesson.index", methods={"GET"})
     */
    public function index(LessonRepository $lessonRepository, Request $request): Response
    {
        $search = new LessonSearch();
        $form = $this->createForm(LessonSearchType::class, $search);
        $form->handleRequest($request);

        return $this->render('admin/lesson/index.html.twig', [
            'lessons' => $lessonRepository->findLessons($search),
            'form' => $form->createView(),
            'current_menu' => 'lesson',
        ]);
    }

    /**
     * @Route("/new", name="admin.lesson.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $lesson = new Lesson();
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lesson);
            $entityManager->flush();

            return $this->redirectToRoute('admin.lesson.index');
        }

        return $this->render('admin/lesson/new.html.twig', [
            'lesson' => $lesson,
            'form' => $form->createView(),
            'current_menu' => 'lesson',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.lesson.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Lesson $lesson): Response
    {
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.lesson.index');
        }

        return $this->render('admin/lesson/edit.html.twig', [
            'lesson' => $lesson,
            'form' => $form->createView(),
            'current_menu' => 'lesson',
        ]);
    }

    /**
     * @Route("/{id}", name="admin.lesson.delete")
     */
    public function delete(Request $request, Lesson $lesson): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lesson->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($lesson);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.lesson.index');
    }
}
