<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Allow the takeaway owner to customise the look of their page
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Styles extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		// Load the default and styles languages
		$this->lang->load('user/default');
		$this->lang->load('user/styles');
	}
	
	public function index()
	{
		// If not a takeaway owner then redirect to homepage
        if (!$this->user->isTakeawayOwner())
			redirect(base_url());
		
		// Load the takeaway model
		$this->load->model('takeaway_model','takeaway');
		
		// If there is POST data
		if (count($_POST) > 0) {
			// Update the takeaway appearance settings
			$this->takeaway->updateTakeawayAppearance($this->user->getTakeawayId(),
							 $_POST['background'],
							 $_POST['fontsize'],
							 $_POST['button'],
							 $_POST['category']
							);
		}		
		
		// Get the specified takeaways details
		$data['takeaway'] = $this->takeaway->getDetails($this->user->getTakeawayId());

		// Set the page to be full width
        $this->template->set('fullwidth', true);
        
		// Load the styles view
		$this->template->load('user/styles', $data);   
    }
}
