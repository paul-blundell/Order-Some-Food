<?php  

/**
 * Takeaway registration form
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Register extends CI_Controller
{
	/**
	 * Main method to register the takeaway
	 */
        function index()  
        {
		// Load the takeaway language
		$this->lang->load('takeaway');
		// Load the takeaway model
		$this->load->model('takeaway_model','takeaway');
		$failures = array();
		
		// If there is POST data
		if (count($_POST) > 0) {

			$userId = 0;
			
			// Check for required fields
			if ($_POST['takeawayname'] == "")
				$failures['takeawayname'] = $this->lang->line('reqbusiness');
			if ($_POST['address1'] == "")
				$failures['address1'] = $this->lang->line('reqaddress1');
			if ($_POST['town'] == "")
				$failures['town'] = $this->lang->line('reqtown');
			if ($_POST['postcode'] == "")
				$failures['postcode'] = $this->lang->line('reqpostcode');
			if ($_POST['shortname'] == "")
				$failures['shortname'] = $this->lang->line('reqshortname');
				
			if ($this->takeaway->checkShortNames($_POST['shortname']))
				$failures['shortname'] = $this->lang->line('shortnametaken');
			
			// If there is no failures and there is user registration details included
			if (count($failures) == 0 && isset($_POST['regemail']) && $_POST['regemail'] != "" && $_POST['regpassword'] != "") {
				// Check passwords match and the email address does not already exist
				if ($_POST['regpassword'] != $_POST['regconfirmpassword'] || $this->user->checkEmailExists($_POST['regemail'])) {
					$failures['regemail'] = $this->lang->line('nopasswordmatch');
				} else {
					$userId = $this->user->register($_POST['regemail'],$_POST['regpassword'], 2);
				}
			// If there is no failures and login details posted
			} else if (count($failures) == 0 && isset($_POST['email']) && $_POST['email'] != "" && $_POST['password'] != "") {
				// Attempt to login in the user
				if (!$this->user->login($_POST['email'], $_POST['password']))
				    $failures['email'] = $this->lang->line('loginincorrect');
			}
			
			// Get the user ID
			if ($userId == 0 && $this->user->isLoggedIn())
				$userId = $this->user->getUid();
			
			// If no errors then submit the data to the database
			if (count($failures) == 0) {
				
				// Get the lat/long values of the takeaways location
				$latlong = $this->getLatLong($_POST['address1'].', '.$_POST['town'].', '.$_POST['postcode']);
				
				// Add the new takeaway
				$this->takeaway->addTakeaway($_POST['takeawayname'],
							     $_POST['shortname'],
							     $_POST['address1'].'<br/>'.$_POST['address2'].'<br/>'.$_POST['town'],
							     $_POST['postcode'],
							     $_POST['phone'],
							     $_POST['description'],
							     $_POST['deliveryCharge'],
							     $_POST['deliveryTime'],
							     $_POST['category'],
							     $latlong['lat'],
							     $latlong['lng'],
							     $userId);
				
				// Redirect to confirmation page
				redirect(base_url('takeaway/register/done'));
			}
			
		}
		
		// Load the possible takeaway categories
		$data['categories'] = array();
		foreach ($this->takeaway->getCategories() AS $category)
			$data['categories'][$category->category_id] = $category->category_name;
		
		// Set any failure messages
		$data['failures'] = $failures;
		
		// Load the template and set the javascript
		$this->template->set('javascript', $this->_javascript());
		$this->template->load('takeaway/register', $data);  
        }

	/**
	 * Show the confirmation message
	 */
	public function done()
	{
		$this->lang->load('takeaway');
		$this->template->load('takeaway/register_complete');  
	}
	
	/**
	 * Check the chosen shortname is available
	 *
	 * @param String shortname
	 */
	public function check($name)
	{
		// Load the takeaway language and model
		$this->lang->load('takeaway');
		$this->load->model('takeaway_model','takeaway');
		// Check to see if the shortname already exists
		if ($this->takeaway->checkShortNames($name))
			echo '<span class="red">'.$this->lang->line('notavailable').'</span>';
		else
			echo '<span class="green">'.$this->lang->line('available').'</span>';
	}
	
	/**
	 * Get Latitude and Longitude by using Google Api's
	 *
	 * @param String address
	 */
	function getLatLong($address)
	{
		// Remove spaces from the address
		$address = str_replace(" ", "", $address);
		
		if ($this->config->item('base_country') != '')
			$address .= ','.$this->config->item('base_country');
		
		// Create the URL
		$google = "https://maps.googleapis.com/maps/api/geocode/xml?address=".$address."&sensor=true";
		
		if ($this->config->item('google_api_key') != '')
			$google .= '&key='.$this->config->item('google_api_key');
			
		// Send the request
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $google);
		curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	    
		$output = curl_exec($ch);
	    
		curl_close($ch);
	     
		// Load in the response
		$xml = new SimpleXMLElement($output);
		
		// If the latitude and longitude is found
		if (isset($xml->result->geometry->location->lat)) {
		return array("lat" => $xml->result->geometry->location->lat,
			     "lng" => $xml->result->geometry->location->lng);
		}
		
		return false;
	}
	
	/**
	 * Javascript to be included in the page
	 */
	private function _javascript()
	{
		return '<script type="text/javascript">
		$(document).ready(function() {
			$("#login").hide();
		});
		
		function showLogin()
		{
			$("#login").show();
			$("#register").hide();
		}
		function showRegister()
		{
			$("#login").hide();
			$("#register").show();
		}
		function checkAvailability()
		{
			$.ajax({
				url: "'.base_url().'takeaway/register/check/"+$("#shortname").val(),
				success: function(data) {
				       $("#shortnameresponse").html(data);
				}
			});
		}
	
		</script>';
	}
} 
