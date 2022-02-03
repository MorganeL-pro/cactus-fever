<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id"="\d+"})
     */
    public function add(int $id): Response
    {
        $product = $this->productRepository->find($id);
        if (is_null($product)) {
            throw $this->createNotFoundException("Le produit $id n'existe pas !");
        }
        $this->cartService->add($id);
        $this->addFlash("success", "Le produit a bien été ajouté à votre panier.");

        return $this->redirectToRoute('products_show', [
            'id' => $id,
        ]);
    }

    /**
     * @Route("/cart", name="cart_show")
     */
    public function show(): Response
    {
        $detailedCart = $this->cartService->getDetailedCartItems();
        $total = $this->cartService->getTotal();
        return $this->render('cart/index.html.twig', [
            'items' => $detailedCart,
            'total' => $total,
        ]);
    }
}
