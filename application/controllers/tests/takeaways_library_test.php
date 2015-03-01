<?php
require_once(APPPATH . '/controllers/tests/Toast.php');

/**
 * Test the takeaway library
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Takeaways_library_test extends Toast
{
	private $takeawayId;
	function __construct()
	{
		parent::__construct(__FILE__);
	}

	function _pre()
	{
		// Add a new takeaway into the database
		$this->takeawayId = $this->takeawaymodel->addTakeaway('testtakeaway', 'testtake', 'test', 'te573t', '555', 'test', 1, 30, 1, 54, -40, 1);
	}

	function _post()
	{
		// Remove the takeaway
		$this->takeawaymodel->removeTakeaway($this->takeawayId);
	}
	
	/**
	 * Test no logo available
	 */
	function test_no_takeaway_logo()
	{
		$result = $this->takeaways->getLogo($this->takeawayId);
		
		if ($result)
			$this->_assert_equals($result, 'images/nologo.gif');
		else
			$this->_fail();
	}
	
	/**
	 * Test logo available
	 */
	function test_takeaway_logo()
	{
		// Save a logo
		mkdir(getcwd().'/images/users/'.$this->takeawayId.'/');
		$path = 'images/users/'.$this->takeawayId.'/logo.jpg';
		
		copy(getcwd().'/images/nologo.gif', getcwd().'/'.$path);
	
		$result = $this->takeaways->getLogo($this->takeawayId);

		if ($result)
			$this->_assert_equals($result, $path);
		else
			$this->_fail();
			
		unlink(getcwd().'/'.$path);
		rmdir(getcwd().'/images/users/'.$this->takeawayId.'/');
	}
}
