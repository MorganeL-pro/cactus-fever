<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasesListController extends AbstractController
{
    /**
     * @Route("/purchases/{id}", name="purchase_index", requirements={"id"="\d+"}, methods={"GET"})
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour accéder à vos commandes.")
     */
    public function index(User $user): Response
    {
        return $this->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
    }
}
