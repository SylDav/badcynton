<?php

namespace App\Controller\Admin;

use App\Entity\Payment;
use App\Entity\PaymentSearch;
use App\Form\PaymentType;
use App\Form\PaymentSearchType;
use App\Repository\PaymentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/payment")
 */
class AdminPaymentController extends AbstractController
{
    /**
     * @Route("/", name="admin.payment.index", methods={"GET"})
     */
    public function index(PaymentRepository $paymentRepository, Request $request): Response
    {
        $search = new PaymentSearch();
        $form = $this->createForm(PaymentSearchType::class, $search);
        $form->handleRequest($request);

        return $this->render('admin/payment/index.html.twig', [
            'payments' => $paymentRepository->findPayments($search),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="admin.payment.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($payment);
            $entityManager->flush();

            return $this->redirectToRoute('admin.payment.index');
        }

        return $this->render('admin/payment/new.html.twig', [
            'payment' => $payment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.payment.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Payment $payment): Response
    {
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.payment.index');
        }

        return $this->render('admin/payment/edit.html.twig', [
            'payment' => $payment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.payment.delete")
     */
    public function delete(Request $request, Payment $payment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$payment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($payment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.payment.index');
    }
}
