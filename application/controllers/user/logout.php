<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Logout a user
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Logout extends CI_Controller
{
	public function index()
	{
		// If the user is logged in, then log them out
		if ($this->user->isLoggedIn())
			$this->user->logout();
			
		// Redirect to the homepage
		redirect(base_url());
	}
	
}
