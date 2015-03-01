<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Login a user
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Login extends CI_Controller
{	

	public function index()
	{
		// Load the default user language file
		$this->lang->load('user/default');
		
		// If the email and password found
		if (isset($_POST['email']) && isset($_POST['pass'])) {
			// Attempt to login in the user
			if ($this->user->login($_POST['email'],$_POST['pass']))
				// Redirect to the confirmation URL if successful
				redirect($_POST['confirmurl']);
			else
				redirect($_POST['failedurl'].'?login=false');

		} else {
			// Check the user is already logged in and if so redirect
			if ($this->user->isLoggedIn())
				redirect(base_url('user/account'));
				
			// Otherwise load the login form view
			$this->template->load('user/login');
		}
	}	
}
