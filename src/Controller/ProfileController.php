<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(): Response
    {
        // if user is an admin, redirect to the dashboard
        if ($this->getUser() instanceof User) {
            $roles = $this->getUser()->getRoles();
            if (in_array("ROLE_ADMIN", $roles)) {
                return $this->redirectToRoute('admin');
            }
        }
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
}
