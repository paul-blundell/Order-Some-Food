<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Display previous orders by the user
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Orders extends CI_Controller
{
	public function index()
	{
		// Load the default and orders language files
		$this->lang->load('user/default');
		$this->lang->load('user/orders');
		
		// Load the order model
		$this->load->model('order_model','orders');
		
		// Check to see if the user has any previous orders
		$data['orders'] = false;
		$orders = $this->orders->getOrdersForUser($this->user->getUid());
		if ($orders)
			$data['orders'] = $orders;
			
		$this->template->set('fullwidth', true); // Make template full width
		$this->template->load('user/orders', $data); // Load the orders view
	}
}
