<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Home controller.
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Home extends CI_Controller
{
	public function index()
	{
		// Load the home page language file
		$this->lang->load('home');
		
		// Check to see if there is the GET parameter 'invalid'
		$data['invalid'] = (isset($_GET['invalid'])) ? true : false;
		
		// Load the home view
		$this->template->load('home', $data);
	}
}
