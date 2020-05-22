<?php

namespace App\Controller\Admin;

use App\Entity\Season;
use App\Entity\Payment;
use App\Form\SeasonType;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/season")
 */
class AdminSeasonController extends AbstractController
{
    /**
     * @Route("/", name="admin.season.index", methods={"GET"})
     */
    public function index(SeasonRepository $seasonRepository): Response
    {
        return $this->render('admin/season/index.html.twig', [
            'seasons' => $seasonRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin.season.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $season = new Season();
        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($season);

            // Création d'un Payment pour chaque joueur de ce club
            foreach ($season->getClub()->getUsers() as $user) {
                $payment = new Payment();
                $payment->setUser($user);
                $payment->setSeason($season);
                $payment->setAmount($season->getAmount());
                $entityManager->persist($payment);
            }
            $entityManager->flush();

            return $this->redirectToRoute('admin.season.index');
        }

        return $this->render('admin/season/new.html.twig', [
            'season' => $season,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.season.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Season $season): Response
    {
        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.season.index');
        }

        return $this->render('admin/season/edit.html.twig', [
            'season' => $season,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.season.delete")
     */
    public function delete(Request $request, Season $season): Response
    {
        if ($this->isCsrfTokenValid('delete'.$season->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($season);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.season.index');
    }
}
