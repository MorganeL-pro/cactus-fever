<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticController extends AbstractController
{
    /**
     * @Route("/informations/cgu", name="static_cgu")
     */
    public function cgu(): Response
    {
        return $this->render('informations/cgu.html.twig');
    }

    /**
     * @Route("/informations/help", name="static_help")
     */
    public function help(): Response
    {
        return $this->render('informations/help.html.twig');
    }

    /**
     * @Route("/informations/legal", name="static_legal")
     */
    public function legal(): Response
    {
        return $this->render('informations/legal.html.twig');
    }

    /**
     * @Route("/informations/rgpd", name="static_rgpd")
     */
    public function rgpdl(): Response
    {
        return $this->render('informations/rgpd.html.twig');
    }
}
