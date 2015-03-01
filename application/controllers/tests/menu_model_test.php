<?php
require_once(APPPATH . '/controllers/tests/Toast.php');

/**
 * Test the Menu model
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Menu_model_test extends Toast
{
	private $takeawayId, $categoryId, $categoryPosition, $menuId;
	private $categoryName, $menuName;
	
	function __construct()
	{
		parent::__construct(__FILE__);
		$this->load->model('menu_model','menu');
	}

	function _pre()
	{
		// Default values used for testing and expected results
		$this->takeawayId = 1;
		$this->categoryPosition = 0; // Default starting position
		$this->categoryName = 'testcat';
		$this->menuName = 'testitem';
		
		$this->categoryId = $this->menu->addCategory($this->categoryName,$this->takeawayId);
		$this->menuId = $this->menu->addMenuItem($this->menuName,'',2.99,$this->categoryId, 0);
	}

	function _post()
	{
		// Remove the category and any products under the category
		$this->menu->removeCategory($this->categoryId);
	}
	
	/**
	 * Test a valid category ID
	 */
	function test_get_category_valid_id()
	{
		$result = $this->menu->getCategory($this->categoryId);
		
		if ($result)
			$this->_assert_equals($result->category_name, $this->categoryName);
		else
			$this->_fail();
	}
	
	/**
	 * Test an invalid category ID
	 */
	function test_get_category_invalid_id()
	{
		$result = $this->menu->getCategory(2000);
		
		$this->_assert_false($result);
	}

	/**
	 * Test getting an items details with a valid ID
	 */
	function test_get_item_details_valid_id()
	{
		$result = $this->menu->getDetails($this->menuId);
		
		if ($result)
			$this->_assert_equals($result->name, $this->menuName);
		else
			$this->_fail();
	}
	
	/**
	 * Test getting an items details with an invalid ID
	 */
	function test_get_item_details_invalid_id()
	{
		$result = $this->menu->getDetails(2000);
		
		$this->_assert_false($result);
	}
	
	/**
	 * Check an item exists
	 */
	function test_check_item_exists()
	{
		$result = $this->menu->checkExists($this->menuId);
		
		$this->_assert_true($result);
	}
	
	/**
	 * Upate an items details
	 */
	function test_update_item()
	{
		$description = 'a new description';
		
		$this->menu->updateMenuItem($this->menuId, $this->menuName, $description, 2.99);
		
		$result = $this->menu->getDetails($this->menuId);
		
		if ($result)
			$this->_assert_equals($result->description, $description);
		else
			$this->_fail();
	}
	
	/**
	 * Check an items ownership
	 */
	function test_item_ownership()
	{
		$result = $this->menu->confirmItem($this->menuId, $this->takeawayId);
		
		$this->_assert_true($result);
	}
	
	/**
	 * Test the categories position updating
	 */
	function test_category_position()
	{
		$this->categoryPosition = 2;
		
		$this->menu->setCategoryPosition($this->categoryId, $this->categoryPosition);
		
		$result = $this->menu->getCategory($this->categoryId);
		
		if ($result)
			$this->_assert_equals($result->position, $this->categoryPosition);
		else
			$this->_fail();
	}
	
	/**
	 * Test the takeaway is open
	 */
	function test_takeaway_open()
	{
		$result = $this->menu->isTakeawayOpen($this->takeawayId);
		
		$this->_assert_true($result);
	}
	
	/**
	 * Test inserting a rating for a takeaway
	 */
	function test_insert_takeaway_rating()
	{
		$this->menu->insertRating($this->takeawayId, 3, '127.0.0.1');
		
		$result = $this->menu->checkRated('127.0.0.1');
		
		if ($result)
			$this->_assert_equals($result->rating, 3);
		else
			$this->_fail();
			
		// Cleanup
		$this->menu->removeRating('127.0.0.1');
	}
	
	/**
	 * Test updating a rating previously made
	 */
	function test_update_takeaway_rating()
	{
		$this->menu->insertRating($this->takeawayId, 3, '127.0.0.1');
		
		$this->menu->updateRating($this->takeawayId, 1, '127.0.0.1');
		
		$result = $this->menu->checkRated('127.0.0.1');
		
		if ($result)
			$this->_assert_equals($result->rating, 1);
		else
			$this->_fail();
		
		// Cleanup
		$this->menu->removeRating('127.0.0.1');
	}
	
}
