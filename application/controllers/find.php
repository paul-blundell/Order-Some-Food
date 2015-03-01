<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Display the list of closest takeaways
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Find extends CI_Controller
{
	public function index()
	{
		// Load the home and takeaway language files
		$this->lang->load('home');
		$this->lang->load('takeaway');
		
		// If no location then redirect back to homepage
        if (!isset($_GET['location']) || $_GET['location'] == $this->lang->line('defaultval'))
            redirect(base_url());
		
		// Post location to the takeaway API
		$this->load->library('curl');
		$result = $this->curl->simple_post('api/takeaway', array('key'=>API_KEY,
									 'signature'=>sha1(API_KEY.API_SECRET.$_GET['location']),
									 'location'=>$_GET['location']));
		
		// Receive takeaway list for the location
		$xml = simplexml_load_string($result);
		
		// If no XML returned then location invalid
		if($xml===FALSE || isset($xml->error)) {
			redirect(base_url().'?invalid='.$xml->error);
		} else {
			// Display takeaway list
			$data['xml'] = $xml;
			$this->template->set('javascript', $this->javascript());
			$this->template->load('takeaway/list', $data);
		}  
	}
	
	/**
	 * Javascript will sort the takeaway list based on most popular,
	 * alphabetically and closest to location.
	 */
	public function javascript()
	{
		return '<script type="text/javascript">
			$(document).ready(function() {
				$(".filter").change(function() {
					var val = $(".filter :selected").val();
					var dir = "asc";
					if (val == "rating")
						dir = "desc";
					
					var mylist = $("#takeaway-list");
					var listitems = mylist.children(".takeaway").get();
					listitems.sort(function(a, b) {
					    var compA = $(a).children(".sort"+val).text().toUpperCase();
					    var compB = $(b).children(".sort"+val).text().toUpperCase();
					   
					    if (dir == "asc")
						return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
					    else
						return (compA > compB) ? -1 : (compA < compB) ? 1 : 0;
					})
					$.each(listitems, function(idx, itm) { mylist.append(itm); });
				});
			});

			</script>';
	}
}
