<?php

namespace App\Controller;

use App\Service\CalendarService;
use App\Service\LessonService;
use App\Repository\LessonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("")
 */
class HomeController extends AbstractController
{

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(LessonRepository $lessonRepository, LessonService $lessonService, CalendarService $calendarService): Response
    {
        if($this->getUser() == null)
        {
            return $this->redirectToRoute('login');
        }

        $lessons = $lessonRepository->findAllFor5MonthsByUser($this->getUser()->getClub());


        return $this->render('index.html.twig', [
            'dates' => $calendarService->createCalendar($lessons, 'user'),
            'alerts' => $lessonService->getAlerts(),
            'trans_day_hash' =>  $calendarService->getTransDay(),
            'trans_month_hash' => $calendarService->getTransMonth(),
        ]);
    }
}
