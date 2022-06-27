<?php

namespace App\Cart;

use App\Cart\CartItem;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
* Cart Service is used in Cart Controller, PurchasedConfirmationControllern and
* in navbar with Twig config file.
* It allows to getCart from Session, save it in Session, empty it, decrement, add or remove product,
* and getTotal for the product.
*/
class CartService
{
    protected SessionInterface $session;
    protected ProductRepository $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    // Returns cart from Session
    protected function getCart(): array
    {
        return $this->session->get('cart', []);
    }

    // Saves cart in Session
    protected function saveCart(array $cart): void
    {
        $this->session->set('cart', $cart);
    }

    // Empties cart and saves it
    public function empty()
    {
        $this->saveCart([]);
    }

    // Adds a product or increase quantity in cart and saves it
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

    // Removes a product in cart and saves it
    public function remove(int $id): void
    {
        $cart = $this->getCart();
        unset($cart[$id]);
        $this->saveCart($cart);
    }

    // Decrements quantity of a product in cart and saves it
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

    //  Returns total of all the products in cart
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
     * getDetailedCartItems() returns a CartItem with product and quantity
     * It can be used to add a list of all CartItems in a purchase
     * (see PurchaseConfirmationController)
     * @return CartItem[]
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
