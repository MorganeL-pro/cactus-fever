<?php

namespace App\Cart;

use App\Entity\Product;
use Exception;

/**
* A cart item is part of Cart Service that takes a product and the quantity
* added to the cart and returns the total with getTotal() method.
*/

class CartItem
{
    public $product;
    public $quantity;

    public function __construct(Product $product, int $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function getTotal(): int
    {
        if ($this->product->getPrice() < 0) {
            throw new Exception('Price can\'t be negative');
        }
        return $this->product->getPrice() * $this->quantity;
    }
}
