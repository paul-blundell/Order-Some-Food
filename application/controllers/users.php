<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User login form
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Users extends CI_Controller
{
	public function index()
	{
		// No direct access to controller
		redirect(base_url());
	}
	
	/**
	 * Login a user
	 */
	public function login()
	{
		// If an email and password recieved
		if (isset($_POST['email']) && isset($_POST['pass'])) {
			// Attempt to login in the user
			if ($this->user->login($_POST['email'],$_POST['pass']))
				redirect($_POST['confirmurl']);
			else
				redirect($_POST['failedurl'].'?login=false');

		} else {
			// If the user is already logged redirect to the account
			if ($this->user->isLoggedIn())			
				redirect(base_url('user/account'));
				
			// Show the login form
			$this->template->load('user/login');
		}
	}
	
	/**
	 * Logout a user
	 */
	public function logout()
	{
		if ($this->user->isLoggedIn())
			$this->user->logout();
			
		redirect(base_url());
	}
	
}
