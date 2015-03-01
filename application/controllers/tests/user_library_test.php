<?php
require_once(APPPATH . '/controllers/tests/Toast.php');

/**
 * Test the user library
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class User_library_test extends Toast
{
	private $userId, $group;
	private $email, $password;
	
	function __construct()
	{
		parent::__construct(__FILE__);
	}

	function _pre()
	{
		// Set up a user for testing purposes
		$this->email = 'testclass@testing.test';
		$this->password = 'test';
		$this->group = 1;
		
		$this->userId = $this->user->register($this->email,$this->password,$this->group);
	}

	function _post()
	{
		// Log out the user and remove from database
		$this->user->logout();
		$this->usermodel->removeUser($this->userId);
	}
	
	/**
	 * Test the user login
	 */
	function test_login_user()
	{
		$result = $this->user->login($this->email, $this->password);
		
		$this->_assert_true($result);
	}
	
	/**
	 * Test an invalid user login
	 */
	function test_login_invalid_user()
	{
		$result = $this->user->login('invalidemail@notin.database', 'test');
		
		$this->_assert_false($result);
	}
	
	/**
	 * Test the user is not logged in
	 */
	function test_user_not_logged_in()
	{
		$result = $this->user->isLoggedIn();
		
		$this->_assert_false($result);
	}
	
	/**
	 * Test the user is logged in
	 */
	function test_user_logged_in()
	{
		$login = $this->user->login($this->email, $this->password);
		
		$this->_assert_true($login);
		
		$result = $this->user->isLoggedIn();
		
		$this->_assert_true($result);
	}
	
	/**
	 * Test the user is not an admin
	 */
	function test_user_is_not_admin()
	{
		$result = $this->user->isAdmin();
		
		$this->_assert_false($result);
	}
	
	/**
	 * Tets the user is an admin
	 */
	function test_user_is_admin()
	{
		// Register an admin user
		$userId = $this->user->register('testclass2@testing.test','test',3);
		
		// Login the admin user
		$result = $this->user->login('testclass2@testing.test','test');
		
		$this->_assert_true($result);
		
		$result = $this->user->isAdmin();
		
		$this->_assert_true($result);
		
		$this->user->logout();
		$this->usermodel->removeUser($userId);
	}
	
	/**
	 * Test user is a not takeaway owner
	 */
	function test_user_is_not_takeaway_owner()
	{
		$result = $this->user->isTakeawayOwner();
		
		$this->_assert_false($result);
	}
	
	/**
	 * Test user is a takeaway owner but has no takeaways associated to it
	 */
	function test_user_is_takeaway_owner_group_no_takeaways()
	{
		// Register a takeaway owner
		$userId = $this->user->register('testclass2@testing.test','test',2);
		
		// Login new user
		$result = $this->user->login('testclass2@testing.test','test');
		
		$this->_assert_true($result);
		
		// Will return false as need to have a registered takeaway first
		$result = $this->user->isTakeawayOwner();
		
		$this->_assert_false($result);
		
		$this->user->logout();
		$this->usermodel->removeUser($userId);
	}

	/**
	 * Test user is a takeaway owner and has a takeaway associated
	 */
	function test_user_is_takeaway_owner_group_with_takeaways()
	{
		// Register a takeaway owner
		$userId = $this->user->register('testclass2@testing.test','test',2);
		
		// Login the new user
		$result = $this->user->login('testclass2@testing.test','test');
		$this->_assert_true($result);
		
		// Add fake takeaway
		$this->load->model('takeaway_model','takeaway');
		$takeawayId = $this->takeaway->addTakeaway('testtakeaway', 'testtake', 'test', 'te573t', '555', 'test', 1, 30, 1, 54, -40, $userId);
		$this->takeaway->setType($takeawayId, 1);
		
		$result = $this->user->isTakeawayOwner();
		$this->_assert_true($result);
		
		$this->user->logout();
		$this->usermodel->removeUser($userId);
		$this->takeaway->removeTakeaway($takeawayId);
	}
	
	/**
	 * Test get a list of takeaways owned by the user
	 */
	function test_get_takeaways_owned_by_user()
	{
		$login = $this->user->login($this->email, $this->password);
		$this->_assert_true($login);
		
		// Create a fake takeaway assigned to our user
		$this->load->model('takeaway_model','takeaway');
		$takeawayId = $this->takeaway->addTakeaway('testtakeaway', 'testtake', 'test', 'te573t', '555', 'test', 1, 30, 1, 54, -40, $this->userId);
		$this->takeaway->setType($takeawayId, 1);
		
		$result = $this->user->getTakeaways();
		
		if ($result)
			$this->_assert_equals(count($result), 1);
		else
			$this->_fail();
			
		$this->takeaway->removeTakeaway($takeawayId);	
	}
	
	/**
	 * Test getting the takeaway ID
	 */
	function test_get_takeawayId()
	{
		$login = $this->user->login($this->email, $this->password);
		$this->_assert_true($login);
		
		// Create a fake takeaway assigned to our user
		$this->load->model('takeaway_model','takeaway');
		$takeawayId = $this->takeaway->addTakeaway('testtakeaway', 'testtake', 'test', 'te573t', '555', 'test', 1, 30, 1, 54, -40, $this->userId);
		$this->takeaway->setType($takeawayId, 1);
		
		$result = $this->user->getTakeawayId();
		
		if ($result)
			$this->_assert_equals($result, $takeawayId);
		else
			$this->_fail();
			
		$this->takeaway->removeTakeaway($takeawayId);	
	}

	/**
	 * Check to see if an email exists already
	 */
	function test_check_email_exists()
	{
		$result = $this->user->checkEmailExists($this->email);
		
		$this->_assert_true($result);
	}
	
	/**
	 * Check to see if an email does not exist
	 */
	function test_check_email_does_not_exist()
	{
		$result = $this->user->checkEmailExists('invalidemail@notin.database');
		
		$this->_assert_false($result);
	}
}
