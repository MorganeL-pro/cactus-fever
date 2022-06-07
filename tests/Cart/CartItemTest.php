<?php

declare(strict_types=1);

namespace tests;

use App\Cart\CartItem;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

final class CartItemTest extends TestCase
{
    public function testClassCartItemExists(): void
    {
        $this->assertTrue(class_exists("App\Cart\CartItem"));
    }

    public function testGetTotalExists(): void
    {
        $this->assertTrue(method_exists("App\Cart\CartItem", "getTotal"));
    }

    public function testHasProductAttribute(): void
    {
        $this->assertClassHasAttribute("product", "App\Cart\CartItem");
    }

    public function testHasQuantityAttribute(): void
    {
        $this->assertClassHasAttribute("quantity", "App\Cart\CartItem");
    }

    public function testGetTotalIsCorrect(): void
    {
        $product = new Product();
        $product->setPrice(300);
        $cartItem = new CartItem($product, 2);
        $this->assertEquals(600, $cartItem->getTotal());
    }

    public function testGetTotalIsCorrect2(): void
    {
        $product = new Product();
        $product->setPrice(122);
        $cartItem = new CartItem($product, 3);
        $this->assertEquals(366, $cartItem->getTotal());
    }

    public function testGetTotalIsCorrect3(): void
    {
        $product = new Product();
        $product->setPrice(0);
        $cartItem = new CartItem($product, 3);
        $this->assertEquals(0, $cartItem->getTotal());
    }

    public function testGetTotalIsCorrect4(): void
    {
        $product = new Product();
        $product->setPrice(5425827);
        $cartItem = new CartItem($product, 4);
        $this->assertEquals(21703308, $cartItem->getTotal());
    }

    public function testGetTotalThrowException(): void
    {
        $product = new Product();
        $product->setPrice(-100);
        $cartItem = new CartItem($product, 2);
        $this->expectException(\Exception::class);
        $cartItem->getTotal();
    }

    public function testGetTotalIsWrong(): void
    {
        $product = new Product();
        $product->setPrice(122);
        $cartItem = new CartItem($product, 3);
        $this->assertNotEquals(600, $cartItem->getTotal());
    }
}
