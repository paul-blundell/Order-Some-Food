<?php
require_once(APPPATH . '/controllers/tests/Toast.php');

/**
 * Test the user model
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class User_model_test extends Toast
{
	private $userId, $group;
	private $email, $password;
	
	function __construct()
	{
		parent::__construct(__FILE__);
	}

	function _pre()
	{
		// Set up a fake user for testing
		$this->email = 'testclass@testing.test';
		$this->password = 'test';
		$this->group = 1;
		
		$this->userId = $this->usermodel->register($this->email,$this->password,$this->group);
	}

	function _post()
	{
		// Remove the user
		$this->usermodel->removeUser($this->userId);
	}
	
	/**
	 * Test the user login
	 */
	function test_login_user()
	{
		$result = $this->usermodel->login($this->email, $this->password);
		
		if ($result)
			$this->_assert_equals($result->uId, $this->userId);
		else
			$this->_fail();
	}
	
	/**
	 * Test the user login for an invalid user
	 */
	function test_login_invalid_user()
	{
		$result = $this->usermodel->login('invalidemail@notin.database', 'test');
		
		$this->_assert_false($result);
	}
	
	/**
	 * Test getting the users email
	 */
	function test_get_email()
	{
		$result = $this->usermodel->getEmail($this->userId);
		
		if ($result)
			$this->_assert_equals($result->email, $this->email);
		else
			$this->_fail();
	}
	
	/**
	 * Test getting an encrypted password, used for password comparisons
	 * during login
	 */
	function test_get_encrypted_password()
	{
		$result = $this->usermodel->getPass($this->userId);
		
		if ($result)
			$this->_assert_equals($result->password, sha1($this->email.'_'.$this->password));
		else
			$this->_fail();
	}
	
	/**
	 * Check email exists
	 */
	function test_check_email_exists()
	{
		$result = $this->usermodel->checkEmail($this->email);
		
		$this->_assert_true($result);
	}
	
	/**
	 * Check email does not exist
	 */
	function test_check_email_does_not_exist()
	{
		$result = $this->usermodel->checkEmail('invalidemail@notin.database');
		
		$this->_assert_false($result);
	}
	
	/**
	 * Test the last order address is correct
	 */
	function test_last_order_address()
	{
		// Create a fake order for our user
		$this->load->model('order_model','order');
		$orderId = $this->order->createOrder($this->userId,1,'test','test','test','test','te573t','555','',2);
		
		$result = $this->usermodel->getLastAddress($this->userId);
		
		if ($result)
			$this->_assert_equals($result->address1, 'test');
		else
			$this->_fail();
			
		$this->order->removeOrder($orderId);	
	}
	
	/**
	 * Test no takeaways owner by the user
	 */
	function test_no_takeaways_owned_by_user()
	{
		$result = $this->usermodel->getTakeaways($this->userId);
		
		$this->_assert_false($result);
	}
	
	/**
	 * Test the takeaways owner by the user
	 */
	function test_takeaways_owned_by_user()
	{
		// Create a fake takeaway assigned to our user
		$this->load->model('takeaway_model','takeaway');
		$takeawayId = $this->takeaway->addTakeaway('testtakeaway', 'testtake', 'test', 'te573t', '555', 'test', 1, 30, 1, 54, -40, $this->userId);
		$this->takeaway->setType($takeawayId, 1);
		
		$result = $this->usermodel->getTakeaways($this->userId);
		
		if ($result)
			$this->_assert_equals(count($result), 1);
		else
			$this->_fail();
			
		$this->takeaway->removeTakeaway($takeawayId);	
	}
	
	/**
	 * Test the user is a takeyaway owner
	 */
	function test_user_is_takeaway_owner()
	{
		// Create a fake takeaway assigned to our user
		$this->load->model('takeaway_model','takeaway');
		$takeawayId = $this->takeaway->addTakeaway('testtakeaway', 'testtake', 'test', 'te573t', '555', 'test', 1, 30, 1, 54, -40, $this->userId);
		$this->takeaway->setType($takeawayId, 1);
		
		$result = $this->usermodel->checkUserisOwner($takeawayId, $this->userId);
		
		$this->_assert_true($result);
			
		$this->takeaway->removeTakeaway($takeawayId);	
	}
}
