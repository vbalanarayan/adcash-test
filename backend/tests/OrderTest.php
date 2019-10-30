<?php
namespace OM\SubOM\Test;
require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR .'autoload.php';

use PHPUnit\Framework\TestCase;
use OM\SubOM\Order;
use \PDO;

class OrderTest extends TestCase {
	protected $db;
	public function setUp() : void {
		$this->db = $this->getConnection();
	}
	public function testUnitPrice() {
		$Order = new Order($this->db);
		$this->assertEquals(
			1.8,
			$Order->getUnitPrice(1),
			'Price of the first product is 1.8'
		);
	}

	public function testTotalPrice() {
		$Order = new Order($this->db);
		$this->assertEquals(
			5.12,
			$Order->calculateTotalPrice(2, 1.6, 4),
			'Total Price after discount should be 5.12 EUR'
		);
	}

	protected function getConnection() {
		return new PDO("mysql:host=localhost:3307;dbname=adcash","root", "root");
	}
}