<?php

namespace App\Cart;

use App\Cart\CartItem;
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

    protected function getCart(): array
    {
        return $this->session->get('cart', []);
    }

    protected function saveCart(array $cart): void
    {
        $this->session->set('cart', $cart);
    }

    public function empty()
    {
        $this->saveCart([]);
    }

    public function add(int $id): void
    {
        $cart = $this->getCart();

        if (is_array($cart) && array_key_exists($id, $cart)) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->saveCart($cart);
    }

    public function remove(int $id): void
    {
        $cart = $this->getCart();
        unset($cart[$id]);
        $this->saveCart($cart);
    }

    public function decrement(int $id): void
    {
        $cart = $this->getCart();
        if (is_array($cart) && !array_key_exists($id, $cart)) {
            return;
        }
        if ($cart[$id] === 1) {
            unset($cart[$id]);
        } else {
            $cart[$id]--;
        }
        $this->saveCart($cart);
    }

    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->getCart() as $id => $quantity) {
            $product = $this->productRepository->find($id);

            if ($product == null) {
                continue;
            }

            $total += $product->getPrice() * $quantity;
        }
        return $total;
    }

    /**
     * @return Cartitem[]
     */
    public function getDetailedCartItems(): array
    {
        $detailedCart = [];
        $cart = $this->getCart();
        foreach ($cart as $id => $quantity) {
            $product = $this->productRepository->find($id);

            if ($product == null) {
                continue;
            }

            $detailedCart[] = new CartItem($product, $quantity);
        }
        return $detailedCart;
    }
}
