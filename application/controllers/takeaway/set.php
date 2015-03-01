<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Set the takeaway being viewed in the session
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Set extends CI_Controller
{
	public function index()
	{
		// If there is POST data
		if (count($_POST) > 0) {
			// Save the takeaway ID to the session
			$this->session->set_userdata('takeawayId', $_POST['takeaway']);
			// Redirect to the orders page
			redirect(base_url('takeaway/orders'));
		}
		
		// Otherwise redirect to the homepage
		redirect(base_url());
	}
}
