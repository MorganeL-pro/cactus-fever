<?php

namespace App\Controller;

use App\Repository\PurchaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/admin/purchases", name="admin_purchases")
     */
    public function adminPurchases(PurchaseRepository $purchaseRepository): Response
    {
        return $this->render('admin/purchases.html.twig', [
            'purchases' => $purchaseRepository->findAll()
        ]);
    }
}
