<?php

namespace App\Controller\Admin;

use App\Service\CalendarService;
use App\Repository\LessonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminHomeController extends AbstractController
{

    /**
     * @Route("/", name="admin.index", methods={"GET"})
     */
    public function index(LessonRepository $lessonRepository, CalendarService $calendarService): Response
    {
        $lessons = $lessonRepository->findAll();

        $dates = $calendarService->createCalendar($lessons, 'admin');

        $trans_day_hash = $calendarService->getTransDay();
        $trans_month_hash = $calendarService->getTransMonth();

        return $this->render('admin/index.html.twig', [
            'dates' => $dates,
            'trans_day_hash' => $trans_day_hash,
            'trans_month_hash' => $trans_month_hash,
            'current_menu' => 'index',
        ]);
    }
}
