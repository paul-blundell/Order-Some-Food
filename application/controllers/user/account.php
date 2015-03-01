<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Modify the users account settings
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Account extends CI_Controller
{
	public function index()
	{
		// Load the default user language file
		$this->lang->load('user/default');
		$failures = array();

		if (count($_POST) > 0) {

			// Check for required fields
			if ($_POST['email'] == "")
				$failures['email'] = $this->lang->line('reqemail');

			if (!$this->user->isAdminSwitched() && ($_POST['pass'] == "" || !$this->user->checkUser($_POST['orig-email'], $_POST['pass'])))
				$failures['pass'] = $this->lang->line('incorrectpass');

			if ($_POST['newpass'] != $_POST['newpass-confirm'])
				$failures['newpass'] = $this->lang->line('nopasswordmatch');
			
			if (count($failures) == 0) {
				$pass = $_POST['pass'];
				if ($_POST['newpass'] != "")
					$pass = $_POST['newpass'];

				$this->user->updateUser($this->user->getUid(), $_POST['email'], $pass);

				redirect(base_url('user/account?done'));
			}
		}
		
		// If the user is not logged in then redirect to login form
		if (!$this->user->isLoggedIn())
			redirect(base_url('user/login'));

		// Set any failure messages
		$data['failures'] = $failures;
		
		// Get the users email
		$data['email'] = $this->user->getEmail();
		// Set the width to full
		$this->template->set('fullwidth', true);
		// Load the account view
		$this->template->load('user/account', $data);
	}
}
