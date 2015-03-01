<?php  

/**
 * User API.
 * This API will allow a user to be registered or confirm a users
 * login details are correct.
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Api_User extends CI_Controller
{
        public function index()  
        {  
        }
	
	/**
	 * Login a user.
	 * Wont actually login a user but instead confirm the login details
	 * are correct and then supply extra details.
	 */
	public function login()
	{
		// Load the user model
		$this->load->model('user_model','usermodel');
		
		// If the expected paramters are found then login the user
		if (isset($_POST['email']) && isset($_POST['pass'])) {
			$result = $this->usermodel->login($_POST['email'],$_POST['pass']);
			// If login was successful
			if ($result) {
				$data['status'] = "true";
				$data['uid'] = $result->uId;
				$data['name'] = $result->name;
				$data['group'] = $result->groupId;
			}
		} else {
			// If login was unsuccessful 
			$data['status'] = "false";
		}
		
		$this->load->view('api/user_view',$data);
	}
	
	/**
	 * Register a new user
	 */
	public function register()
	{
		// Load the user model
		$this->load->model('user_model','usermodel');
		
		// If the expected paramters are found then register the user
		if (isset($_POST['email']) && isset($_POST['pass'])) {
			$result = $this->usermodel->register($_POST['email'],$_POST['pass'],1);
			// If registrationw as successful
			if ($result) {
				$data['status'] = "true";
				$data['uid'] = $result;
			}
		} else {
			$data['status'] = "false";
		}
	
		$this->load->view('api/user_view',$data);
	}
} 
