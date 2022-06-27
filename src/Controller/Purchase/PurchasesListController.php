<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * PurchasesListController gets all the purchases from a user.
 * Only registered user can access this route
 */
class PurchasesListController extends AbstractController
{
    /**
     * It returns all the purchases for the user
     * @Route("/purchases/{id}", name="purchase_index", requirements={"id"="\d+"}, methods={"GET"})
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour accéder à vos commandes.")
     */
    public function index(User $user): Response
    {
        if ($this->getUser() == $user) {
            return $this->render('purchase/index.html.twig', [
                'purchases' => $user->getPurchases()
            ]);
        } else {
            return $this->redirectToRoute('purchase_index', ['id' => $this->getUser()->getId()]);
        }
    }
}
