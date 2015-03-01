<?php  

/**
 * Order API.
 * This API will recieve an XML string containing the details of an order.
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Order extends CI_Controller
{
        public function index()  
        {
		// Load the order and api models
		$this->load->model('order_model','order');
		$this->load->model('api_model','api');

		// Check there is signature and the order details
		if (!isset($_POST['signature']) || !isset($_POST['order'])) 
			return $this->showError('Rejected', 'Missing required data.');
		
		// Get the secret key for the supplied API key
		$secret = $this->api->getSecret($_POST['key']);

		// Check secret key is found and the signature supplied matches
		if (!$secret || strtoupper($_POST['signature']) != strtoupper(sha1($_POST['key'].$secret->client_secret.$_POST['order']))) 
			return $this->showError('Rejected', 'Unauthorised Access.');
		
		// Load the XML string
		$xml = simplexml_load_string($_POST['order']);
		
		// Get the takeaway details from the supploed takeaway ID
		$takeawayDetails = $this->takeaways->getDetails($xml->takeaway);
	
		// If could not find takeaway details, reject order as invalid takeaway
		if (!$takeawayDetails) 
			return $this->showError('Rejected', 'Invalid takeaway.');
		
		// If could not find required fields, reject order
		if (!isset($xml->address1) || !isset($xml->town) || !isset($xml->postcode) || !isset($xml->delivery)) 
			return $this->showError('Rejected', 'Missing required data.');
		
		// If could not find any items, reject order
		if (!$xml->item) 
			return $this->showError('Rejected', 'No items in the order.');
		
		// If takeaway is closed, reject order
		if (!$takeawayDetails->status) 
			return $this->showError('Rejected', $takeawayDetails->name.' is currently closed. Please try again later.');

		// Register new user? (Assumption that password/email checks done)
		if (isset($xml->register))
			$xml->uid = $this->user->register($xml->register->email,$xml->register->pass);
		
		// Create the new order
		$data['userId'] = (isset($xml->uid)) ? $xml->uid : 0;
		$data['orderId'] = $this->order->createOrder($data['userId'],
						$xml->takeaway,
						$xml->name,
						$xml->address1,
						$xml->address2,
						$xml->town,
						$xml->postcode,
						$xml->telephone,
						$xml->comments,
						$xml->delivery,
						$xml->paid);
		
		// Loop through products in the order and store
		foreach ($xml->item AS $item)
			$this->order->addItem($data['orderId'], $item->id, $item->price, $item->qty);
		
		// Return confirmation XML and order ID
		$data['status'] = "Accepted";
		$this->load->view('api/order_view', $data);
        }
        
        private function showError($status, $message)
        {
        	$data = array();
        	$data['status'] = $status;
		$data['message'] = $message;
		$this->load->view('api/order_view', $data);
        }
} 
