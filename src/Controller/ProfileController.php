<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     * @IsGranted("ROLE_USER")
     */
    public function index(PurchaseRepository $purchaseRepository): Response
    {

        $lastPurchase = $purchaseRepository->findOneBy(['user' => $this->getUser()], ['id' => 'DESC' ]);

        // if user is an admin, redirect to the dashboard
        if ($this->getUser() instanceof User) {
            $roles = $this->getUser()->getRoles();
            if (in_array("ROLE_ADMIN", $roles)) {
                return $this->redirectToRoute('admin');
            }
        }
        return $this->render('profile/index.html.twig', [
            'lastPurchase' => $lastPurchase
        ]);
    }

    /**
     * @Route("/profile/edit/{id}", name="profile_edit")
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profile/edit.html.twig', [
            'user' => $user,
            'registrationForm' => $form,
        ]);
    }

    /**
     * @Route("/delete", name="profile_delete", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('profile', [], Response::HTTP_SEE_OTHER);
    }
}
