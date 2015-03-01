<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Review and place an order
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Order extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		// Load the order language file
		$this->lang->load('order');
	}
	
	public function index()
	{
		// No direct access
		redirect(base_url());
	}
	
	/**
	 * Load the order review
	 *
	 * @param Integer takeaway ID
	 * @param String takeaway shortname
	 */
	public function takeaway($takeaway, $name = "")
	{
		// Set theme to default
		$custom = false;
		// URL to the takeaways menu
		$menuUrl = base_url().'menu/takeaway/'.$takeaway;
		
		// If takeaway value is 0 and name is specified
		// then using a custom url (url.com/takeaway) so display
		// custom theme and load takeaway data
		if ($takeaway == 0 && $name != "") {
			$this->load->model('menu_model','menu');
			// Get the takeaway details from the shortname
			$takeawayId = $this->menu->getTakeawayFromShortName($name);
			// If a takeaway has been found
			if ($takeawayId) {
				$takeaway = $takeawayId->takeawayId;
				$custom = $name;
				// Update the URL to the menu
				$menuUrl = base_url().$name;
			} else {
				// Redirect to homepage if no takeaway found
				redirect(base_url());
			}
		}

		// Load session data
		$order = $this->session->userdata('order');
		// Load the takeaway model
		$this->load->model('takeaway_model','takeaway');
		
		// Check some items exists in the takeaways basket
		if (isset($order[$takeaway]) && count($order[$takeaway]) > 0) {
			$data['order'] = $order[$takeaway];
			$data['total'] = 0;
			
			// Loop through each takeaway and add price to final total
			foreach($order[$takeaway] AS $item) {
				$data['total']+= $item['price'] * $item['qty'];
			}
			
			// Get the takeaways details
			$data['takeaway'] = $this->takeaway->getDetails($takeaway);
			
			// Add delivery charge to final total
			$data['total'] += $data['takeaway']->deliveryCharge;

			// Get the users details - inc previous address used
			$data['user'] = $this->user->getUserDetails();
			
			// Check session for previous post data - if so load that data instead
			$orderDetails = $this->session->userdata('order-details');
			if ($orderDetails) {
				$data['user']['delivery_name'] = $orderDetails['delivery_name'];
				$data['user']['address1'] = $orderDetails['address1'];
				$data['user']['address2'] = $orderDetails['address2'];
				$data['user']['town'] = $orderDetails['town'];
				$data['user']['postcode'] = $orderDetails['postcode'];
				$data['user']['telephone'] = $orderDetails['telephone'];
				if (isset($orderDetails['register']))
					$data['user']['register'] = $orderDetails['register'];
				if (isset($orderDetails['email']))
					$data['user']['email'] = $orderDetails['email'];
			}
			
			// Check to see if error saving form
			$data['register'] = true;
			if (isset($_GET['register']))
				$data['register'] = false;
		
			$data['menuUrl'] = $menuUrl;
			// Display the order page
			$this->template->set('javascript', $this->javascript());
			$this->template->load('order/review', $data, $custom);
			$this->session->set_userdata(array('custom'=>$custom,'menuUrl'=>$menuUrl));
		} else {
			redirect($menuUrl);
		}
	}
	
	/*
	 * Review the order and Select payment option
	 *
	 */
	public function review()
	{
		if (count($_POST) == 0)
			redirect($this->session->userdata('menuUrl'));
			
		$this->session->set_userdata('order-details', $_POST);

		// Load the takeaway model
		$this->load->model('takeaway_model','takeaway');

		$takeaway = $this->takeaway->getDetails($_POST['takeawayId']);

		$total = 0;
		$order = $this->session->userdata('order');
		foreach($order[$takeaway->takeawayId] AS $item) {
				$total+= $item['price'] * $item['qty'];
		}
		$total += $takeaway->deliveryCharge;

		$this->load->library('paypal');
		$this->paypal->init($takeaway->paypalEmail, $takeaway->paypalPassword, $takeaway->paypalSignature, base_url('order/confirm'), base_url('order/cancel'), "https://api-3t.paypal.com/nvp");       
		
		$data['button'] = $this->paypal->generateButton($total);
		$data['form'] = $_POST;

		$this->load->view('order/payment', $data);
	}

	/*
	 * PayPal payment confirmation
	 */
	public function confirm()
	{
		$amount = $this->session->userdata('order-amount');
		$orderDetails = $this->session->userdata('order-details');
		$this->load->model('takeaway_model','takeaway');
		$takeaway = $this->takeaway->getDetails($orderDetails['takeawayId']);

		$this->load->library('paypal');
		$this->paypal->init($takeaway->paypalEmail, $takeaway->paypalPassword, $takeaway->paypalSignature, base_url('order/confirm'), base_url('order/cancel'), "https://api-3t.paypal.com/nvp");       
		
		$confirm = $this->paypal->getConfirmation($_GET['PayerID'], $_GET['token'], $amount);
                
                if ($confirm) {
			$this->complete(sha1($orderDetails['delivery_name'].'_'.CHECKOUT_KEY), true);
		} else {
			echo 'There has been an error processing your request. Please try again.';
		}
	}

	/*
	 * Payment Cancel
	 */
	public function cancel()
	{
		$this->template->load('order/cancel');
	}

	/*
	 * Place the order - Used if no paypal and bypassing payment selection form
	 */
	public function placeorder()
	{
		if (count($_POST) == 0)
			redirect($this->session->userdata('menuUrl'));
			
		if (isset($_POST['paymentReview'])) {
			$orderDetails = $this->session->userdata('order-details');
		} else {
			$this->session->set_userdata('order-details', $_POST);
			$orderDetails = $_POST;
		}
		$this->complete(sha1($orderDetails['delivery_name'].'_'.CHECKOUT_KEY));
	}
	

	/**
	 * Process and place order
	 */
	private function complete($token, $paid = 0)
	{
		$order = $this->session->userdata('order');
		$orderDetails = $this->session->userdata('order-details');

		// Token prevents direct access to the 'complete' stage
		if ($token != sha1($orderDetails['delivery_name'].'_'.CHECKOUT_KEY))
			redirect($this->session->userdata('menuUrl'));
	
		// Create a new XML object
		$xml = new SimpleXMLElement("<order></order>");
		
		// Check to see if registration request and if so check passwords match and email does not exist
		if (isset($orderDetails['register']) && $orderDetails['register']) {
			if ($orderDetails['pass'] == null || $orderDetails['pass'] != $orderDetails['pass-confirm'] || $this->user->checkEmailExists($orderDetails['email']))
				redirect($orderDetails['returnurl'].'?register=false');
				
			$register = $xml->addChild('register');
			$register->addChild('email',$orderDetails['email']);
			$register->addChild('pass',$orderDetails['pass']);
		}	
		
		// Add the details to the XMl object
		$xml->addChild('takeaway', $orderDetails['takeaway']);
		$xml->addChild('name', $orderDetails['delivery_name']);
		$xml->addChild('address1', $orderDetails['address1']);
		$xml->addChild('address2', $orderDetails['address2']);
		$xml->addChild('town', $orderDetails['town']);
		$xml->addChild('postcode', $orderDetails['postcode']);
		$xml->addChild('telephone', $orderDetails['telephone']);
		$xml->addChild('comments', $orderDetails['comments']);
		$xml->addChild('delivery', $orderDetails['delivery']);
		$xml->addChild('paid', $paid);
		
		// Add the products select to the XML object
		foreach ($order[$orderDetails['takeaway']] AS $menuId=>$details) {
			$item = $xml->addChild('item');
			$item->addChild('id', $menuId);
			$item->addChild('price', $details['price']);
			$item->addChild('qty', $details['qty']);
		}
		
		$post = array();
		$post['key'] = API_KEY;
		$post['signature'] = sha1(API_KEY.API_SECRET.$xml->asXML());
		$post['order'] = $xml->asXML();
	
		// Send details to API to process
		$this->load->library('curl');
		$result = $this->curl->simple_post('api/order', $post);
		
		// Process the XML response from the API
		$xml = simplexml_load_string($result);
		
		$this->load->model('takeaway_model','takeaway');
		$data['takeaway'] = $this->takeaway->getDetails($orderDetails['takeaway']);
		$data['takeawayname'] = $data['takeaway']->name;
		$data['menuUrl'] = $this->session->userdata('menuUrl');

		// If order accepted
		if ($xml && $xml->status == 'Accepted') {
			// Clear order session
			$this->session->unset_userdata('order');
			$this->template->load('order/accepted', $data, $this->session->userdata('custom'));
		} else {
			$data['error'] = $xml->message;
			$this->template->load('order/rejected', $this->session->userdata('custom'));
		}
	}
        
	/**
	 * Javscript to be included on the page
	 */
        private function javascript()
        {
		return '<script	type="text/javascript">
			$(document).ready(function() {
				$(".register").hide();	
		
				if($(".register-box").is(":checked"))
					$(".register").show();
					
				$(".register-box").change(function(){
					if($(this).is(":checked")){
						$(".register").show();
					} else {
						$(".register").hide();
					}
				});
				$("#delivery-method").change(function() 
				{
					if($(this).attr("value") == 2){
						$("#delivery-details").show();
					} else {
						$("#delivery-details").hide();
					}
				});
			});
			
			function removeItem(takeaway, id) {
				$.ajax({
					url: "'.base_url().'menu/remove/"+takeaway+"/"+id,
					success: function(data) {
                                               parent.window.location.reload();
					}
				});
			}

			$("#proceed").click(function() {
				$("#loading").html("<img src=\"'.base_url('images/loading-small.gif').'\" alt=\"Loading..\" />");
				$.ajax({
					   type: "POST",
					   url: "'.base_url('order/review').'",
					   data: $("#detailsForm").serialize(),
					   success: function(data)
					   {
					       $("#checkout-wrapper").html(data);
					   }
				});

				return false;
			});
			</script>';
        }
}
