<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class will display the registered takeaways that are awaiting approval
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Awaiting extends CI_Controller
{
	public function index()
	{
		// Load the default admin language file
		$this->lang->load('admin/default');
		
		// If not an admin then redirect to the homepage
		if (!$this->user->isAdmin())
			redirect(base_url());
			
		// Load the takeaway model
		$this->load->model('takeaway_model','takeaway');
			
		// If there is POST data
		if (count($_POST) > 0) {
			// Loop through the statuses that have been POSTed
			foreach($_POST['status'] AS $takeawayId=>$value) {
				// If the value is 3, the takeaway has been reject so needs to be removed
				if ($value == 3)
					$this->takeaway->removeTakeaway($takeawayId);
				else // Otherwise just the set takeaway type to equal this value
					$this->takeaway->setType($takeawayId, $value);
			}
		}

		// Load the takeaways that are awaiting approval
		$data['takeaways'] = $this->takeaway->getAwaiting();
	
		// Set the template to load using the full width template
		$this->template->set('fullwidth', true);
		// Load the admin template
		$this->template->load('admin/awaiting', $data);
	}

}
