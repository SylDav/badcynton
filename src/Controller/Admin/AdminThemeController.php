<?php

namespace App\Controller\Admin;

use App\Entity\Theme;
use App\Form\ThemeType;
use App\Repository\ThemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/theme")
 */
class AdminThemeController extends AbstractController
{
    /**
     * @Route("/", name="admin.theme.index", methods={"GET"})
     */
    public function index(ThemeRepository $themeRepository): Response
    {
        return $this->render('admin/theme/index.html.twig', [
            'themes' => $themeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin.theme.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $theme = new Theme();
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($theme);
            $entityManager->flush();

            return $this->redirectToRoute('admin.theme.index');
        }

        return $this->render('admin/theme/new.html.twig', [
            'theme' => $theme,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.theme.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Theme $theme): Response
    {
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.theme.index');
        }

        return $this->render('admin/theme/edit.html.twig', [
            'theme' => $theme,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.theme.delete")
     */
    public function delete(Request $request, Theme $theme): Response
    {
        if ($this->isCsrfTokenValid('delete'.$theme->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($theme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.theme.index');
    }
}
