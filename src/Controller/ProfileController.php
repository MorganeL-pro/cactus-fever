<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\EditPasswordType;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
     * @Route("/profile/edit/{id}", requirements={"id"="\d+"}, name="profile_edit")
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
     * @Route("/delete/{id}", name="profile_delete", requirements={"id"="\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage
    ): Response {
        $session = $request->getSession();
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->flush();
            $tokenStorage->setToken(null);
            $session->invalidate();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('profile', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * editPassword() handles EditPasswordType that allows a user
     * to change her or his password. It hashes the password before
     * set it to User. It also sends an email to User to inform
     * her or him that password has been modified.
     * @Route("/profile/edit-password", name="edit_password")
     */
    public function editPassword(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        MailerInterface $mailer
    ) {
        $user = $this->getUser();
        $form = $this->createForm(EditPasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plaintextPassword = $form['plainPassword']->getData();
            if (is_string($plaintextPassword) && $user instanceof User) {
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $plaintextPassword
                );
                $user->setPassword($hashedPassword);
                $entityManager->flush();
                $this->addFlash("success", "Le mot de passe a bien été modifié.");
                $email = (new TemplatedEmail())
                    ->from('admin@cactusfever.com')
                    ->to(new Address($user->getEmail()))
                    ->subject('Mot de passe modifié')
                    ->htmlTemplate('emails/password-modified.html.twig')
                    ->context([
                        'username' => $user->getEmail(),
                    ])
                ;
                $mailer->send($email);
                return $this->redirectToRoute('profile', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('profile/edit-password.html.twig', [
            'form' => $form,
        ]);
    }
}
