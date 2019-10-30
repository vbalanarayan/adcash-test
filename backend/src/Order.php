<?php
namespace OM\SubOM;

use \PDO;
class Order
{
	protected $db;
	//Discounted item is hardcoded for convenience, it can be moved to config in actual project
	const DISCOUNT_ITEM_ID = 2;

	function __construct ($dbobj)
	{
		$this->db = $dbobj;
	}

	function getUnitPrice ($productId)
	{
		$sql = "SELECT unit_price FROM product WHERE product_id = :product_id";
		$query = $this->db->prepare($sql);
		$query->bindParam(':product_id',$productId,PDO::PARAM_INT);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
		return $result['unit_price'];
	}

	function addOrder ($userId, $productId, $quantity)
	{
		$unitPrice = $this->getUnitPrice($productId);
		$totalPrice = $this->calculateTotalPrice($productId, $unitPrice, $quantity);
		$sql = "INSERT INTO orders (user_id, product_id, quantity, total_price) 
			VALUES(:user_id, :product_id, :quantity, :total_price)";
		$query = $this->db->prepare($sql);
		$query->bindParam(':user_id',$userId,PDO::PARAM_INT);
		$query->bindParam(':product_id',$productId,PDO::PARAM_INT);
		$query->bindParam(':quantity',$quantity,PDO::PARAM_INT);
		$query->bindParam(':total_price',$totalPrice,PDO::PARAM_STR);
		$query->execute();

		return $this->db->lastInsertId();
	}

	function editOrder ($userId, $productId, $quantity, $orderId)
	{
		$unitPrice = $this->getUnitPrice($productId);
		$totalPrice = $this->calculateTotalPrice($productId, $unitPrice, $quantity);
		$sql = "UPDATE orders 
				SET user_id = :user_id, product_id = :product_id, quantity = :quantity, total_price = :total_price 
				WHERE order_id = :order_id";
		$query = $this->db->prepare($sql);
		$query->bindParam(':user_id',$userId,PDO::PARAM_INT);
		$query->bindParam(':product_id',$productId,PDO::PARAM_INT);
		$query->bindParam(':quantity',$quantity,PDO::PARAM_INT);
		$query->bindParam(':total_price',$totalPrice,PDO::PARAM_STR);
		$query->bindParam(':order_id',$orderId,PDO::PARAM_INT);
		
		return $query->execute();
	}

	function getOrderList ($searchParams = [])
	{
		$sql = "SELECT a.order_id, a.product_id, a.user_id, a.quantity, a.total_price, DATE_FORMAT(a.created_time,'%d %b %Y, %h:%i%p') as created_time, b.product_name, b.unit_price, c.full_name
		FROM orders a, product b, user c 
		WHERE a.product_id = b.product_id AND a.user_id = c.user_id";

		if ($searchParams['order_period'] == 'today') {
			$sql .= " AND DATE(a.created_time) = CURRENT_DATE()";
		}elseif ($searchParams['order_period'] == '7days') {
			$sql .= " AND DATE(a.created_time) >= CURRENT_DATE() - INTERVAL 7 DAY";
		}
		if (isset($searchParams['search_param'])) {
			$keyword = "%".$searchParams['search_param']."%";
			$sql .= " AND (b.product_name LIKE '$keyword' OR c.full_name LIKE '$keyword')";
		}
		$sql .= " ORDER BY a.order_id DESC";
		$query = $this->db->prepare($sql);
		
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		//file_put_contents("query.log","\n".date('d-m-Y H:i:s')." : Query ".$sql, FILE_APPEND | LOCK_EX);
		return $result;
	}

	function getOrderDetail ($orderId)
	{
		$sql = "SELECT order_id, product_id, user_id, quantity FROM orders WHERE order_id = :order_id";
		$query = $this->db->prepare($sql);
		$query->bindParam(':order_id',$orderId,PDO::PARAM_INT);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
		return $result;
	}

	function calculateTotalPrice ($productId, $unitPrice, $quantity)
	{
		$totalPrice = $unitPrice * $quantity;
		//Check if its the discounted item & matches the rule
		if ($productId == self::DISCOUNT_ITEM_ID && $quantity >= 3) {
			$totalPrice = $totalPrice - ($totalPrice * 0.2);
			$totalPrice = number_format($totalPrice, 2);
		}
		return $totalPrice;
	}

	function deleteOrder ($orderId)
	{
		
		$sql = "DELETE FROM orders WHERE order_id = :order_id";
		$query = $this->db->prepare($sql);
		$query->bindParam(':order_id',$orderId,PDO::PARAM_INT);
		
		return $query->execute();
	}

}