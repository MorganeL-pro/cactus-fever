<?php

namespace App\Controller\Purchase;

use DateTime;
use App\Entity\Purchase;
use App\Cart\CartService;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchaseConfirmationController extends AbstractController
{
    protected $cartService;
    protected EntityManagerInterface $entityManager;
    protected ProductRepository $productRepository;

    public function __construct(
        CartService $cartService,
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository
    ) {
        $this->cartService = $cartService;
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
    }
    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour valider votre commande.")
     */
    public function confirm(Request $request): Response
    {
        $purchase = new Purchase();
        $form = $this->createForm(CartConfirmationType::class, $purchase);
        $form->handleRequest($request);

        // if form is not submitted we eject user
        if (!$form->isSubmitted()) {
            $this->addFlash("warning", "Vous devez remplir le formulaire de confirmation de commande.");
            return $this->redirectToRoute("cart_show");
        }
        // if user's cart is empty, we eject the user
        $cartItems = $this->cartService->getDetailedCartItems();
        if (count($cartItems) === 0) {
            $this->addFlash("warning", "Vous ne pouvez pas valider une commande avec un panier vide.");
            return $this->redirectToRoute("cart_show");
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new DateTime();
            $purchase->setCreatedAt($date);
            $purchase->setTotal($this->cartService->getTotal());
            $purchase->setUser($this->getUser());
            // foreach product added to the cart we create a new purchaseItem
            foreach ($this->cartService->getDetailedCartItems() as $cartItem) {
                $purchaseItem = new PurchaseItem();
                $purchaseItem->setPurchase($purchase)
                    ->setProduct(($cartItem->product))
                    ->setProductName($cartItem->product->getName())
                    ->setQuantity($cartItem->quantity)
                    ->setTotal($cartItem->getTotal())
                    ->setProductPrice($cartItem->product->getPrice());
                $cartItem->product->setQuantity(($cartItem->product->getQuantity()) - ($cartItem->quantity));
                $this->entityManager->persist($purchaseItem);
            }
            $this->entityManager->persist($purchase);
            $this->entityManager->flush();

            // empty card
            $this->cartService->empty();

            $this->addFlash("success", "La commande a bien été validée.");
            return $this->redirectToRoute('purchase_index', ['id' => $this->getUser()->getId()]);
        }
    }
}
