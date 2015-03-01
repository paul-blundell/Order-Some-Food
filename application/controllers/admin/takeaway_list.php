<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class will display a list of takeaways that are registered and approved.
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Takeaway_list extends CI_Controller
{
	public function index()
	{
		// Load the default admin language file
		$this->lang->load('admin/default');
		
		// If not an admin redirect to the homepage
		if (!$this->user->isAdmin())
			redirect(base_url());
		
		// Load the takeaway model
		$this->load->model('takeaway_model','takeaway');
			
		// If there is POST data
		if (count($_POST) > 0) {
			// Loop through the statuses POSTed and set the type
			foreach($_POST['status'] AS $takeawayId=>$value) {
				$this->takeaway->setType($takeawayId, $value);
			}
		}

		// Load the approved takeaways
		$data['takeaways'] = $this->takeaway->getApproved();
	
		// Set the template to be full width and load in the list template
		$this->template->set('fullwidth', true);
		$this->template->load('admin/takeaway_list', $data);
	}

	public function user($id) 
	{
		// Load the current logged in user
		$admin = $this->session->userdata('user');

		// Load the takeaway model
		$this->load->model('takeaway_model','takeaway');

		// Load the user model
		$this->load->model('user_model','usermodel');

		$takeaway = $this->takeaway->getDetails($id);
		$userDetails = $this->usermodel->getDetails($takeaway->userId);

		// Set the login details to that of the takeaway owner
		$user['uid'] = $takeaway->userId;
                $user['name'] = $userDetails->name;
                $user['group'] = $userDetails->groupId;
		$user['admin'] = $admin;
                    
		// Save the data to the session
        $this->session->set_userdata('user', $user);

		redirect(base_url('takeaway/orders'));
	}

	public function reverse()
	{
		// Load the current logged in user
		$admin = $this->session->userdata('user');

		// Set the login details to that of the takeaway owner
		$user['uid'] = $admin['admin']['uid'];
                $user['name'] = $admin['admin']['name'];
                $user['group'] = $admin['admin']['group'];
		unset($user['admin']);
                    
		// Save the data to the session
                $this->session->set_userdata('user', $user);

		redirect(base_url('admin/takeaway_list'));
	}
}
