<?php
require_once(APPPATH . '/controllers/tests/Toast.php');

/**
 * Test the order model
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Order_model_test extends Toast
{
	private $orderId, $userId;
	
	function __construct()
	{
		parent::__construct(__FILE__);
		$this->load->model('order_model','order');
	}

	function _pre()
	{
		// Specifiy the default values that will be used
		$this->userId = 1000;
		$this->orderId = $this->order->createOrder($this->userId,1,'test','test','test','test','te573t','555','',2);
		$this->order->addItem($this->orderId, 1, 2.99, 4);
		$this->order->addItem($this->orderId, 1, 6.99, 2);
	}

	function _post()
	{
		// Remove the order details
		$this->order->removeOrder($this->orderId);	
	}

	/**
	 * Test retrieving orders for a user
	 */
	function test_get_orders_by_user()
	{
		$result = $this->order->getOrdersForUser($this->userId);
		
		if (!$result) {
			$this->_fail();
		} else {
			$found = false;
			foreach ($result AS $order) {
				if ($order->orderId == $this->orderId)
					$found = true;
			}
			$this->_assert_true($found);
		}		
	}
	
	/**
	 * Test retrieving items witin an order
	 */
	function test_get_items_in_order()
	{
		$result = $this->order->getItemsInOrder($this->orderId);
		
		if (!$result) {
			$this->_fail();
		} else {
			$this->_assert_equals(count($result), 2);
		}		
	}
	
	/**
	 * Test setting the order status
	 */
	function test_set_order_status()
	{
		$this->order->setStatus($this->orderId, 1);
		
		$result = $this->order->getOrdersForUser($this->userId);
		
		if (!$result) {
			$this->_fail();
		} else {
			$status = 0;
			foreach ($result AS $order) {
				if ($order->orderId == $this->orderId)
					$status = $order->status;
			}
			$this->_assert_equals($status, 1);
		}		
	}
}
