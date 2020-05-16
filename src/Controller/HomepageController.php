<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("")
 */
class HomepageController extends AbstractController
{

    /**
     * @Route("/homepage", name="homepage", methods={"GET"})
     */
    public function homepage()
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->container->get('security.authorization_checker');

        if ($user->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin.index');
        }

        if ($user->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('index');
        }
        return $this->redirectToRoute('login');
    }
}
