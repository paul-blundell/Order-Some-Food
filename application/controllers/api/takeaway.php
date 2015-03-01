<?php  

/**
 * Takeaway API.
 * This API will return a list of takeaways closest to the specified
 * location.
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Takeaway extends CI_Controller
{
        public function index()  
        {
		// Load the takeaway and api models
		$this->load->model('takeaway_model','takeaway');
		$this->load->model('api_model','api');

		// Check the signature is present and a valid location parameter
		if (!isset($_POST['signature']) || (!isset($_POST['location']) && !isset($_POST['latitude']))) 
		{
			$data['message'] = 'Missing required data.';
			$this->load->view('api/error_view',$data);
			return;
		}
		
		// Get the secret for the supplied api key
		$secret = $this->api->getSecret($_POST['key']);
		
		// If the secret was found
		if ($secret) {
			// If using lat/long values, generate signature using this
			if (isset($_POST['latitude']))
				$match = sha1($_POST['key'].$secret->client_secret.$_POST['latitude']);
			else
				$match = sha1($_POST['key'].$secret->client_secret.$_POST['location']);
		}
			
		// Check secret was found and the signature matches the one generated
		if (!$secret || strtoupper($_POST['signature']) != strtoupper($match)) 
		{
			$data['message'] = 'Unauthorised Access.';
			$this->load->view('api/error_view', $data);
			return;
		}
		
		// If using current location no need to get the LAT/LONG values
		if (isset($_POST['latitude']) && isset($_POST['longitude'])) 
		{
			$data['results'] = $this->takeaway->getData($_POST['latitude'], $_POST['longitude']);
		}
		// Get Lat Long details for sent location
		else 
		{
			$latlng = $this->getLatLong($_POST['location']);
			$data['results'] = false;
			// If the lat/long was found then get the takeaway data
			if ($latlng) 
				$data['results'] = $this->takeaway->getData($latlng['lat'], $latlng['lng']);
		}
		
		// No data found?
		if (!$data['results']) 
		{
			$data['message'] = 'Could not get location.';
			$this->load->view('api/error_view',$data);
			return;
		}
		
		// Output takeaway as XML
		$this->load->view('api/list_view',$data);
		
        }
	
	/**
	 * Get Latitude and Longitude by using Google Api's
	 *
	 * @param String address textual location to lookup
	 */
	private function getLatLong($address)
	{
		// Remove any spaces in the address
		$address = str_replace(" ", "", $address);
		
		if ($this->config->item('base_country') != '')
			$address .= ','.$this->config->item('base_country');

		// Create the URL
		$google = 'https://maps.googleapis.com/maps/api/geocode/xml?address='.$address.'&sensor=true';
		
		if ($this->config->item('google_api_key') != '')
			$google .= '&key='.$this->config->item('google_api_key');
		
		// Send request to the URL
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $google);
		curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	    
		$output = curl_exec($ch);
	    
		curl_close($ch);
	     
		// Process the response
		$xml = new SimpleXMLElement($output);
		
		// If the latitude and longitude was found, then return the lat/long
		if (isset($xml->result->geometry->location->lat)) {
		return array("lat" => $xml->result->geometry->location->lat,
			     "lng" => $xml->result->geometry->location->lng);
		}
		
		// return false if nothing found
		return false;
	}
} 
