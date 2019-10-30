<?php
namespace OM\Test;
require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR .'autoload.php';

use PHPUnit\Framework\TestCase;
use OM\Order;

class OrderTest extends TestCase {
	public function testUnitPrice() {
		$Order = new Order();
		$this->assertEquals(
			1.8,
			$Receipt->total(1),
			'Price of the first product is 1.8'
		);
	}

	public function testTotalPrice() {
		$Order = new Order();
		$this->assertEquals(
			5.12,
			$Receipt->calculateTotalPrice(2, 1.6, 3),
			'Total Price after discount should be 5.12 EUR'
		);
	}
}