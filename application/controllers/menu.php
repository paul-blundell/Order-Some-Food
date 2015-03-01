<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Load the takeaway menu
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Menu extends CI_Controller
{
	private $takeaway = '';
	
	public function __construct()
	{
		parent::__construct();
		// Load the menu language file
		$this->lang->load('menu');
	}
	
	public function index()
	{
		// Redirect to the homepage if accessed directly
                redirect(base_url());
	}
	
	/**
	 * Load the takeaway menu
	 *
	 * @param Integer takeaway ID
	 * @param String takeaway Name
	 */
	public function takeaway($takeaway, $name = "")
	{
		$custom = false;

		// If takeaway value is 0 and name is specified
		// then we are using a custom url (url.com/takeaway) so display
		// custom theme and load takeaway data
		if ($takeaway == 0 && $name != "") {
			$this->load->model('menu_model','menu');
			// Get the takeaway details from the shortname
			$takeawayId = $this->menu->getTakeawayFromShortName($name);
			// Check takeaway is found
			if ($takeawayId) {
				$takeaway = $takeawayId->takeawayId;
				$custom = $name;
			} else {
				// Redirect to homepage
				redirect(base_url());
			}
			
		}
		
		$this->takeaway = $takeaway;
		
		// Post takeaway ID to API and receive the menu
		$this->load->library('curl');
		$result = $this->curl->simple_post('api/menu', array('key'=>API_KEY,
								     'signature'=>sha1(API_KEY.API_SECRET.$this->takeaway),
								     'takeaway'=>$this->takeaway));

		// Load the XML response
		$xml = simplexml_load_string($result);
		
		// If not valid XML
		if($xml===FALSE) {
			echo 'Could not find specified takeaway.';
		} else {
			// If an error is recieved, return to homepage with error message
			if (isset($xml->error))
				redirect(base_url().'?invalid='.$xml->error);
			
			$data['xml'] = $xml;
			
			// Set the javascript
			$this->template->set('javascript', $this->javascript());
			// Load the menu list view
			$this->template->load('menu/list', $data, $custom);
			// Save to the session the custom view
			$this->session->set_userdata(array('custom'=>$custom));
		}
	}

	/**
	 * Add an item to Basket
	 * Called using Ajax.
	 *
	 * @param Integer takeaway ID
	 * @param Integer item ID
	 */
	public function add($takeaway, $id)
	{
		// Load the menu model and load details of the item selected
		$this->load->model('menu_model','menu');
		$results = $this->menu->getDetails($id);
		$name = $results->name;
		
		// Load session data
		$order = $this->session->userdata('order');
		$qty = 1;
		
		// If already added then increase qty
		if (isset($order[$takeaway][$id]))
			$qty = $order[$takeaway][$id]['qty']+1;

		// Check to see if item has a parent - if so prefix with name of parent
		if ($results->parent != 0) {
			$parent = $this->menu->getDetails($results->parent);
			$name = $parent->name.' - '.$results->name;
		}
		
		// Insert/Update item
		$order[$takeaway][$id] = array("id"=>$id,"name"=>$name,"price"=>$results->price,"qty"=>$qty);
		
		// Save session data
		$this->session->set_userdata('order', $order);
	}
	
	/**
	 * Remove an item from the Basket
	 *
	 * @param Integer takeaway ID
	 * @param Integer item ID
	 */
	public function remove($takeaway, $id)
	{
		// Load session data
		$order = $this->session->userdata('order');
		
		// If quantity is more than 1 reduce quantity by one only.
		if (isset($order[$takeaway][$id]) && $order[$takeaway][$id]['qty'] > 1) {
			$qty = $order[$takeaway][$id]['qty']-1;
			$order[$takeaway][$id] = array("id"=>$id,"name"=>$order[$takeaway][$id]['name'],"price"=>$order[$takeaway][$id]['price'],"qty"=>$qty);
		// Otherwise remove item
		} else {
			unset($order[$takeaway][$id]);
		}
		
		// Save session data
		$this->session->set_userdata('order', $order);
	}
	
	/**
	 * Update the Basket sidebar
	 * Called from Ajax
	 *
	 * @param Integer takeaway ID
	 */
	public function update($takeaway)
	{
		// Load the session data
		$order = $this->session->userdata('order');
		
		// Load the menu model
		$this->load->model('menu_model','menu');
		
		// Check the takeaway is open first otherwise show closed message
		if (!$this->menu->isTakeawayOpen($takeaway)) {
			$this->load->view('menu/order/closed');
			
		// Check some data exists
		} else if (isset($order[$takeaway]) && count($order[$takeaway]) > 0) {
			$data['order'] = $order[$takeaway];
			$data['total'] = 0;
			
			// Loop through items and add to final total
			foreach($order[$takeaway] AS $item) {
				$data['total']+= $item['price'] * $item['qty'];
			}
			
			// Load the sidebar view
			$data['takeaway'] = $takeaway;
			$data['orderUrl'] = "order/takeaway/".$takeaway;
			// If we are using the custom view, change the URL to the checkout
			if ($this->session->userdata('custom'))
				$data['orderUrl'] = $this->session->userdata('custom')."/order";
				
			$this->load->view('menu/order/items', $data);
		} else {
			echo 'You have not yet added anything to your order.';
		}
	}
	
	/**
	 * Update user rating for takeaway
	 * Called from Ajax
	 *
	 * @param Integer takeaway ID
	 * @param Integer rating value
	 */
	public function rating($takeaway, $rating)
	{
		// Load the menu model
		$this->load->model('menu_model','menu');
		
		// Get the users IP address
		$ip = $this->session->userdata('ip_address');
		
		// Check to see if the user has previously rated
		if ($this->menu->checkRated($ip))
			$this->menu->updateRating($takeaway, $rating, $ip);
		else
			$this->menu->insertRating($takeaway, $rating, $ip);
	}
	
	/**
	 * Load the your order basket view
	 */
	private function sidebar()
	{
		$view = $this->load->view('menu/order/block', '', true);
		
		return $view;
	}

        /**
	 * Javascript to be included in the page
	 */
        private function javascript()
        {
		return '<script type="text/javascript" src="'.base_url().'js/jquery.rating.pack.js"></script>
			<script type="text/javascript">
            
            $(document).ready(function() {
				$(".rating-star").rating({
					required: true,
					callback: function(value, link){
					$.ajax({
						url: "'.base_url().'menu/rating/'.$this->takeaway.'/"+value,
						success: function(data) {
							$(".ratetext").html("Your Rating");
						},
						cache: false
					});
					}
				});
				update();
			
				categoriesScroll();
				orderScroll();
            });
                        
            $(".jumpto").click(function(){
			    var jump = $(this).attr("name");
			    var new_position = $("#"+jump).offset();
			    window.scrollTo(new_position.left,new_position.top);
			    return false;
			});
			
			function addItem(id) {
				$.ajax({
					url: "'.base_url().'menu/add/'.$this->takeaway.'/"+id,
					success: function(data) {
                                               update();
					},
					cache: false
				});
			}
			
			function removeItem(id) {
				$.ajax({
					url: "'.base_url().'menu/remove/'.$this->takeaway.'/"+id,
					success: function(data) {
                                               update();
					},
					cache: false
				});
			}
			
			function update() {
				$.ajax({
					url: "'.base_url().'menu/update/'.$this->takeaway.'",
					success: function(data) {
                                                $("#order").html(data);
					},
					cache: false
				});
			}
			
			function categoriesScroll() {
				var offset = $(".menu-left").offset();
				var topPadding = 15;
				$(window).scroll(function() {
				  	// Order
					if ($(window).scrollTop() > offset.top) {
					    $(".menu-left").stop().animate({
						marginTop: $(window).scrollTop() - offset.top + topPadding
					    });
					} else {
					    $(".menu-left").stop().animate({
						marginTop: 0
					    });
					};
				});
			}
			
			function orderScroll() {
				var offset = $(".menu-right").offset();
				var topPadding = 15;
				$(window).scroll(function() {
					if ($(window).scrollTop() > offset.top) {
					    $(".menu-right").stop().animate({
						marginTop: $(window).scrollTop() - offset.top + topPadding
					    });
					} else {
					    $(".menu-right").stop().animate({
						marginTop: 0
					    });
					};
				});
			}
            </script>';
        }
}
