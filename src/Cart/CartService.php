<?php

namespace App\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected SessionInterface $session;
    protected ProductRepository $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function add(int $id): void
    {
        $cart = $this->session->get('cart', []);

        if (is_array($cart) && array_key_exists($id, $cart)) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->session->set('cart', $cart);
    }

    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->session->get('cart', []) as $id => $quantity) {
            $product = $this->productRepository->find($id);
            $total += $product->getPrice() * $quantity;
        }
        return $total;
    }

    public function getDetailedCartItems(): array
    {
        $detailedCart = [];
        $cart = $this->session->get('cart', []);
        foreach ($cart as $id => $quantity) {
            $product = $this->productRepository->find($id);
            $detailedCart[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
        }
        return $detailedCart;
    }
}
