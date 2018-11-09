<?php
// tests/Model/CartTest.php
namespace App\Test\Model;

use App\Model\Cart;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
	/**
     * test cart's total price
     */
	public function testGetPrice()
	{
		$product1 = new Product();
		$product1->setId(1);
		$product1->setName('product');
		$product1->setDescription('Desc product');
		$product1->setPrice(10);

		$product2 = new Product();
		$product2->setId(2);
		$product2->setName('product2');
		$product2->setDescription('Desc product 2');
		$product2->setPrice(12);


		$cart = new Cart();

		$cart->modifyCountProduct($product1, 3);
		$cart->modifyCountProduct($product2, 2);

		$cart->removeProduct($product1);

		$cart->modifyCountProduct($product2, -1);

		$result = $cart->getPrice();

		$this->assertEquals(12, $result);
	}
}