<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Display the order statistics and incoming orders
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
		// Load the default user language and the order language
		$this->lang->load('user/default');
		$this->lang->load('user/orders');
		
		// If the user is not a takeaway owner redirect to homepage
		if (!$this->user->isTakeawayOwner())
			redirect(base_url());
		
		// Load the order and takeaway models
		$this->load->model('order_model','orders');
		$this->load->model('takeaway_model','takeaway');
		
		// Get the orders for the takeaway
		$result = $this->orders->getTakeawayOrders($this->user->getTakeawayId());
		$values = array();
		
		// If there is any orders
		if ($result) {
			// Loop through th eorder and set the total
			foreach ($result AS $val) {
				$values[$val->day] = $val->total;	
			}
		}
		
		$data['graph'] = false;
		// If there is order data
		if (count($values) > 0) {
			// Load the graph library
			$this->load->library('graph');
			// Set the location paths to the graph image
			$location = base_url().'images/users/'.$this->user->getTakeawayId().'/';
			$abslocation = getcwd().'/images/users/'.$this->user->getTakeawayId().'/';
			// If the location does not exists then create it
			if (!file_exists($abslocation))
				mkdir($abslocation, 0777);
			
			// Creare the graph with the order data in the specified location
			$this->graph->make($values, getcwd().'/images/users/'.$this->user->getTakeawayId().'/recent.png');
		
			// Set the graph data
			$data['graph'] = $location.'recent.png';
		}
	
		// Get the details of the takeaway
		$data['takeaway'] = $this->takeaway->getDetails($this->user->getTakeawayId());
		
		// Get statistical information, such as the number of orders, total value etc
		$data['numberOfOrders'] = count($this->orders->getTotalTakeawayOrders($this->user->getTakeawayId(), 1));
		$data['numberOfCancelledOrders'] = count($this->orders->getTotalTakeawayOrders($this->user->getTakeawayId(), 2));
		$data['valueOfOrders'] = $this->orders->getValueTakeawayOrders($this->user->getTakeawayId());
		
		$data['completedOrders'] = $this->orders->getTotalTakeawayOrders($this->user->getTakeawayId(), 1);
		$data['cancelledOrders'] = $this->orders->getTotalTakeawayOrders($this->user->getTakeawayId(), 2);
		
		$data['popularProducts'] = $this->orders->getPopularProducts($this->user->getTakeawayId());
		
		$this->template->set('fullwidth', true); // Make template full width
		$this->template->set('javascript', $this->_javascript()); // Set the javascript
		$this->template->load('order/orders', $data);
	
	}
	
	/**
	 * Display the orders which are currently being processed
	 */
	public function processing()
	{
		// Load the orders language file
		$this->lang->load('user/orders');
		// Load the order and takeaway models
		$this->load->model('order_model','orders');
		$this->load->model('takeaway_model','takeaway');
		
		// Get the takeaway details
		$takeaway = $this->takeaway->getDetails($this->user->getTakeawayId());
		
		// Check to see if the takeaway is set to open or closed
		$data['open'] = false;
		if ($takeaway->status == 1)
			$data['open'] = true;
		
		// Get the pending orders from the order model
		$data['orders'] = $this->orders->getPendingOrders($this->user->getTakeawayId());
	
		// Load the processing view
		$this->load->view('order/processing', $data);
	}
	
	/**
	 * Mark an order as complete
	 *
	 * @param Integer orderID
	 */
	public function complete($order)
	{
		$this->load->model('order_model','orders');
		$this->orders->setStatus($order, 1);
		
		redirect(base_url('takeaway/orders/processing'));
	}
	
	/**
	 * Mark an order as canceled
	 *
	 * @param Integer orderId
	 */
	public function cancel($order)
	{
		$this->load->model('order_model','orders');
		$this->orders->setStatus($order, 2);
		
		redirect(base_url('takeaway/orders/processing'));
	}
	
	/**
	 * Set the takeaway to Open
	 */
	public function open()
	{
		$this->load->model('order_model','orders');
		$this->orders->takeawayStatus($this->user->getTakeawayId(),true);
		
		redirect(base_url('takeaway/orders/processing'));
	}

	/**
	 * Set the takeaway to Closed
	 */
	public function close()
	{
		$this->load->model('order_model','orders');
		$this->orders->takeawayStatus($this->user->getTakeawayId(),false);
		
		redirect(base_url('takeaway/orders/processing'));
	}
	
	/**
	 * Javascript to be included in the page
	 */
	private function _javascript()
	{
		return '<script type="text/javascript">
			$(document).ready(function() {
				$("#ordersdatatable").dataTable({"bSort": false});
				$("#populardatatable").dataTable({"bSort": false});
			});
			</script>';
	}

}
