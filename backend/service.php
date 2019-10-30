<?php session_start();
/** This file acts as a backend entry point.
The requests are routed to the relevant functions
This uses a custom design pattern
**/

require('dbconfig.php'); 
require('src/Order.php');

$finaloutput = array();
if(!$_POST) {
	$action = $_GET['action'];
}
else {
	$action = $_POST['action'];
}

switch($action){
    case 'add_order':
        $finaloutput = addOrder();
        break;
    case 'edit_order':
        $finaloutput = editOrder();
        break;
    case 'fetch_order':
        $finaloutput = fetchOrder();
        break;
    case 'fetch_order_detail':
        $finaloutput = fetchOrderDetail();
        break;
    case 'delete_order':
        $finaloutput = deleteOrder();
        break;
    default:
        $finaloutput = array("infocode" => "INVALIDACTION", "message" => "Irrelevant action");
}

echo json_encode($finaloutput);

function addOrder() {
    global $dbh;
	
	$order = new Order($dbh);

    $user_id = trim($_POST['select_user']);
    $product_id = trim($_POST['select_product']);
    $quantity = trim($_POST['order_quantity']);
    
    $result = $order->addOrder($user_id, $product_id, $quantity);
    if ($result) {
        $output = array("infocode" => "ORDERADDED", "message" => 'New Order has been added successfully.<br> Auto-closing the window in 5 seconds', "orderId" => $result);
    } else {
        $output = array("infocode" => "ORDERFAILED", "message" => 'An error occurred while creating new order, please try again');
    }
   
    return $output;
}

function editOrder() {
    global $dbh;
    
    $order = new Order($dbh);

    $user_id = trim($_POST['select_user']);
    $product_id = trim($_POST['select_product']);
    $quantity = trim($_POST['order_quantity']);
    $order_id = trim($_POST['hidden_order_id']);
    
    $result = $order->editOrder($user_id, $product_id, $quantity, $order_id);
    if ($result) {
        $output = array("infocode" => "ORDERUPDATED", "message" => 'Order details has been edited successfully.<br> Auto-closing the window in 5 seconds');
    } else {
        $output = array("infocode" => "ORDERUPDATEFAILED", "message" => 'An error occurred while updating the order, please try again');
    }
   
    return $output;
}

function fetchOrder() {
    global $dbh;
    
    $order = new Order($dbh);

    $searchParams['order_period'] = trim($_POST['order_period']);
    if (isset($_POST['search_param']) && trim($_POST['search_param']) != '') {
        $searchParams['search_param'] = trim($_POST['search_param']);
    }
    
    $result = $order->getOrderList($searchParams);
    if ($result) {
        $output = array("infocode" => "ORDERSFETCHED", "message" => 'Orders have been fetched', "ordersData" => $result);
    } else {
        $output = array("infocode" => "NOORDERS", "message" => 'No orders matching the input parameters');
    }    
    
    return $output;
}

function fetchOrderDetail() {
    global $dbh;
    
    $order = new Order($dbh);
    $orderId = trim($_POST['order_id']);
    $result = $order->getOrderDetail($orderId);
    if ($result) {
        $output = array("infocode" => "ORDERDETAILFETCHED", "message" => 'Order detail has been fetched', "orderData" => $result);
    } else {
        $output = array("infocode" => "INVALIDORDER", "message" => 'Invalid Order Id has been passed');
    }    
    
    return $output;
}

function deleteOrder() {
    global $dbh;
    
    $order = new Order($dbh);

    $order_id = trim($_POST['order_id']);
    
    $result = $order->deleteOrder($order_id);
    if ($result) {
        $output = array("infocode" => "ORDERDELETED", "message" => 'Order details has been deleted successfully.');
    } else {
        $output = array("infocode" => "ORDERNOTDELETED", "message" => 'Unable to delete the order, please try again');
    }
   
    return $output;
}

?>