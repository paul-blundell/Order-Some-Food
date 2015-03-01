<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Edit the details of the takeaway
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Details extends CI_Controller
{
	public function index()
	{
		// Load the default user language file
		$this->lang->load('user/default');
		// Load the takeaway model
		$this->load->model('takeaway_model','takeaway');
		
		// If not a takeaway owner then redirect to homepage
		if (!$this->user->isTakeawayOwner())
			redirect(base_url());

		// Check to make sure photo folder exists for current user
                if (!file_exists(getcwd()."/images/users/".$this->user->getTakeawayId()."/"))
                        mkdir(getcwd()."/images/users/".$this->user->getTakeawayId()."/", 0777);
		
		
		// If there is POST data
		if (count($_POST) > 0) {

			// Upload Logo
			if (isset($_FILES["logo"])) {
				$allowedExts = array("jpg", "jpeg", "png");
				$extension = end(explode(".", $_FILES["logo"]["name"]));
				if ((($_FILES["logo"]["type"] == "image/png")
				|| ($_FILES["logo"]["type"] == "image/jpeg")
				|| ($_FILES["logo"]["type"] == "image/pjpeg"))
				&& ($_FILES["logo"]["size"] < 5000000)
				&& in_array($extension, $allowedExts))
				{
					if ($_FILES["logo"]["error"] > 0) {
						$data['error'] = "Error: " . $_FILES["logo"]["error"] . "<br />";
				    	} else {
						move_uploaded_file($_FILES["logo"]["tmp_name"], getcwd()."/images/users/".$this->user->getTakeawayId()."/logo.jpg");
					}
				} else {
					$data['error'] = $this->lang->line('upload-error');
				}
			}

			// Update the opening times
			foreach ($_POST['day'] AS $code=>$times) {
				$this->takeaway->updateOpeningTimes($this->user->getTakeawayId(),
								    $code,
								    $times[0],
								    $times[1],
								    (isset($times[2])) ? 0 : 1);
			}

			// Update the takeaway information
			$this->takeaway->updateTakeaway($this->user->getTakeawayId(),
							 $_POST['address'],
							 $_POST['postcode'],
							 $_POST['description'],
							 $_POST['delivery'],
							 $_POST['delivery-time'],
							 $_POST['paypal'],
							 $_POST['paypal-email'],
							 $_POST['paypal-signature'],
							 $_POST['paypal-password']
							);
		}
	
		// Get the details of the takeaway
		$data['takeaway'] = $this->takeaway->getDetails($this->user->getTakeawayId());
		// Get the opening times
		$data['openingtimes']  = $this->takeaway->getOpeningTimes($this->user->getTakeawayId());
		// Get the logo of the takeaway
		$data['logo'] = $this->takeaways->getLogo($this->user->getTakeawayId());
		
		// Set the display to be full width and load the template
		$this->template->set('fullwidth', true);
		$this->template->load('user/details', $data);
	}

}
