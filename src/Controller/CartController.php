<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Form\CartConfirmationType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * CartController handles routes for a user's cart and uses
 * methods from CartService
*/
class CartController extends AbstractController
{
    protected ProductRepository $productRepository;
    protected CartService $cartService;

    public function __construct(
        ProductRepository $productRepository,
        CartService $cartService
    ) {
        $this->productRepository = $productRepository;
        $this->cartService = $cartService;
    }
    /**
     * Uses add() method from CartService to add the product in Cart or increase quantity
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id"="\d+"})
     */
    public function add(int $id, Request $request): Response
    {
        // get the product from database
        $product = $this->productRepository->find($id);
        if (is_null($product)) {
            throw $this->createNotFoundException("Le produit $id n'existe pas !");
        }

        // if quantity is null, send an alert to user
        if ($product->getQuantity() <= 0) {
            $this->addFlash("alert", "Désolé, le produit n'est malheureusement plus en stock");
            return $this->redirectToRoute('products');
        }

        // else user can add it and receives an alert
        $this->cartService->add($id);
        $this->addFlash("warning", "Le produit a bien été ajouté à votre panier.");

        // depends on the query string, user is redirected to the previous route
        if ($request->query->get('returnToCart')) {
            return $this->redirectToRoute("cart_show");
        };

        if ($request->query->get('returnToIndex')) {
            return $this->redirectToRoute("products");
        };

        return $this->redirectToRoute('products_show', [
            'id' => $id,
        ]);
    }

    /**
     * Uses getTotal() from CartService to get total of all products
     * and getDetailedCartitems() to get the detail product to product
     * It also returns the form to do a purchase from the cart
     * @Route("/cart", name="cart_show")
     */
    public function show(): Response
    {
        $form = $this->createForm(CartConfirmationType::class);
        $detailedCart = $this->cartService->getDetailedCartItems();
        $total = $this->cartService->getTotal();
        return $this->render('cart/index.html.twig', [
            'items' => $detailedCart,
            'total' => $total,
            'confirmationForm' => $form->createView()
        ]);
    }

    /**
     * Uses remove() method from CartService and sends an alert to User
     * @Route("/cart/delete/{id}", name="cart_delete", requirements={"id"="\d+"})
     */
    public function delete(int $id): Response
    {
        $product = $this->productRepository->find($id);
        if (is_null($product)) {
            throw $this->createNotFoundException("Le produit $id n'existe pas !");
        }

        $this->cartService->remove($id);
        $this->addFlash("warning", "Le produit a bien été supprimé.");
        return $this->redirectToRoute("cart_show");
    }

    /**
     * Uses decrement() method from CartService to decrease quantity or remove the product
     * and sends an alert to User.
     * @Route("/cart/decrement/{id}", name="cart_decrement", requirements={"id"="\d+"})
     */
    public function decrement(int $id): Response
    {
        $product = $this->productRepository->find($id);
        if (is_null($product)) {
            throw $this->createNotFoundException("Le produit $id n'existe pas !");
        }

        $this->cartService->decrement($id);
        $this->addFlash("warning", "La quantité a bien été réduite.");
        return $this->redirectToRoute("cart_show");
    }
}
