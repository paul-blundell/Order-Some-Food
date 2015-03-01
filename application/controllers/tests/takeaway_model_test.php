<?php
require_once(APPPATH . '/controllers/tests/Toast.php');

/**
 * Test the takeaway model
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Takeaway_model_test extends Toast
{
	private $takeawayId;
	function __construct()
	{
		parent::__construct(__FILE__);
		$this->load->model('takeaway_model','takeaway');
	}

	function _pre()
	{
		// Add a new takeaway into the database and get the ID number
		$this->takeawayId = $this->takeaway->addTakeaway('testtakeaway', 'testtake', 'test', 'te573t', '555', 'test', 1, 30, 1, 54, -40, 1);
	}

	function _post()
	{
		// Remove the takeaway
		$this->takeaway->removeTakeaway($this->takeawayId);
	}
	
	/**
	 * Test getting a takeaway from the shortname
	 */
	function test_get_takeaway_from_shortname()
	{
		$result = $this->takeaway->getTakeawayFromShortName('testtake');
		
		if ($result)
			$this->_assert_equals($result->takeawayId, $this->takeawayId);
		else
			$this->_fail();
	}
	
	/**
	 * Test getting the takeaway details
	 */
	function test_get_takeaway_details()
	{
		$result = $this->takeaway->getDetails($this->takeawayId);
		
		if ($result)
			$this->_assert_equals($result->name, 'testtakeaway');
		else
			$this->_fail();
	}
	
	/**
	 * Test getting takeaway that are awaiting approval
	 */
	function test_awaiting_approval()
	{
		$result = $this->takeaway->getAwaiting();
		
		if (!$result) {
			$this->_fail();
		} else {
			$found = false;
			foreach ($result AS $takeaway)
				if ($takeaway->takeawayId==$this->takeawayId)
					$found = true;
			
			$this->_assert_true($found);
		}
	}
	
	/**
	 * Test updating the takeaway type
	 */
	function test_update_type()
	{
		$this->takeaway->setType($this->takeawayId, 1);
		
		// Type set to approved so now should not be in waiting list
		$result = $this->takeaway->getAwaiting();
		
		if (!$result) {
			$this->_fail();
		} else {
			$found = false;
			foreach ($result AS $takeaway)
				if ($takeaway->takeawayId==$this->takeawayId)
					$found = true;
			
			$this->_assert_false($found);
		}
	}
	
	/**
	 * Test checking to see if a shortname exists
	 */
	function test_check_shortnames_existing()
	{
		$result = $this->takeaway->checkShortNames('testtake');
		
		$this->_assert_true($result);
	}
	
	/**
	 * Test checking to see if a shortname does not exist
	 */
	function test_check_shortnames_new()
	{
		$result = $this->takeaway->checkShortNames('anewshortnamethatdoesnotexist');
		
		$this->_assert_false($result);
	}
	
	/**
	 * Test updating the takeaways details
	 */
	function test_update_takeaway()
	{
		$newdescription = 'newdescription';
		$newcharge = 0;
		
		$this->takeaway->updateTakeaway($this->takeawayId, 'test', 'tet573t', $newdescription, $newcharge, 45);
		
		$result = $this->takeaway->getDetails($this->takeawayId);
		
		if ($result) {
			$this->_assert_equals($result->description, $newdescription);
			$this->_assert_equals($result->deliveryCharge, $newcharge);
		} else {
			$this->_fail();
		}
	}
	
}
