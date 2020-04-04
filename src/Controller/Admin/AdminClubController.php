<?php

namespace App\Controller\Admin;

use App\Entity\Club;
use App\Form\ClubType;
use App\Repository\ClubRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/club")
 */
class AdminClubController extends AbstractController
{
    /**
     * @Route("/", name="admin.club.index", methods={"GET"})
     */
    public function index(ClubRepository $clubRepository): Response
    {
        return $this->render('admin/club/index.html.twig', [
            'clubs' => $clubRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin.club.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $club = new Club();
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($club);
            $entityManager->flush();

            return $this->redirectToRoute('admin.club.index');
        }

        return $this->render('admin/club/new.html.twig', [
            'club' => $club,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.club.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Club $club): Response
    {
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.club.index');
        }

        return $this->render('admin/club/edit.html.twig', [
            'club' => $club,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.club.delete")
     */
    public function delete(Request $request, Club $club): Response
    {
        if ($this->isCsrfTokenValid('delete'.$club->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($club);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.club.index');
    }
}
