<?php
require_once(APPPATH . '/controllers/tests/Toast.php');

/**
 * Test the Menu API
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Api_menu extends Toast
{
	private $takeaway;
	
	function __construct()
	{
		parent::__construct(__FILE__);
		// Load the cURL library
		$this->load->library('curl');
	}

	function _pre()
	{
		// Set the takeaway ID to 1
		$this->takeaway = '1';
	}

	function _post() {}

	/**
	 * Test the API with no POST data
	 */
	function test_no_data()
	{
		// POST to the menu API
		$result = $this->curl->simple_post('api/menu', array());
		
		// Load the response and surpress any warnings for this purpose
		$xml = @simplexml_load_string($result);
		
		// If the XML is not valid
		if ($xml === FALSE)
			$this->_fail('Returned invalid XML');
		
		// If there is an error element in the response
		if (isset($xml->error)) {
			$this->_assert_equals($xml->error, 'Missing required data.');
		} else {
			$this->_fail();
		}
	}
	
	/**
	 * Test the API with an invalid key but a correct signature
	 */
	function test_invalid_key()
	{
		// POST to the menu API
		$result = $this->curl->simple_post('api/menu', array('key'=>'ANINVALIDKEY',
									 'signature'=>sha1(API_KEY.API_SECRET.$this->takeaway),
									 'takeaway'=>$this->takeaway));
		
		// Load the response and surpress any warnings
		$xml = @simplexml_load_string($result);
		
		// If not valid XML
		if ($xml === FALSE)
			$this->_fail('Returned invalid XML');
		
		// If there is an error element in the response
		if (isset($xml->error)) {
			$this->_assert_equals($xml->error, 'Unauthorised Access.');
		} else {
			$this->_fail();
		}
	}
	
	/**
	 * Test the API with an invalid signature
	 */
	function test_invalid_signature()
	{
		// POST to the menu API with signature not being encrypted with the location value
		$result = $this->curl->simple_post('api/menu', array('key'=>API_KEY,
								     'signature'=>sha1(API_KEY.API_SECRET),
								     'takeaway'=>$this->takeaway));
		
		// Load the response and surpress any warnings
		$xml = @simplexml_load_string($result);
		
		// If not valid XML
		if ($xml === FALSE)
			$this->_fail('Returned invalid XML');
		
		// If there is an error element in the response
		if (isset($xml->error)) {
			$this->_assert_equals($xml->error, 'Unauthorised Access.');
		} else {
			$this->_fail();
		}
	}
	
	/**
	 * Test the API with no key or signature
	 */
	function test_no_key_or_signature()
	{
		// POST to the menu API
		$result = $this->curl->simple_post('api/menu', array('takeaway'=>$this->takeaway));
		
		// Load the XML response and surpress any warnings
		$xml = @simplexml_load_string($result);
		
		// If not valid XML
		if ($xml === FALSE)
			$this->_fail('Returned invalid XML');
		
		// If there is an error element in the response
		if (isset($xml->error)) {
			$this->_assert_equals($xml->error, 'Missing required data.');
		} else {
			$this->_fail();
		}
	}
	
	/**
	 * Test the API with an invalid takeaway ID
	 */
	function test_invalid_takeawayId()
	{
		// Set the takeaway ID to a value that does not exist
		$this->takeaway = 2000;
		
		// POST data to the menu API
		$result = $this->curl->simple_post('api/menu', array('key'=>API_KEY,
									 'signature'=>sha1(API_KEY.API_SECRET.$this->takeaway),
									 'takeaway'=>$this->takeaway));

		// Load the XML response and surpress any warnings
		$xml = @simplexml_load_string($result);
		
		// If not valid XML
		if ($xml === FALSE)
			$this->_fail('Returned invalid XML');
		
		// If there is an error element in the response
		if (isset($xml->error)) {
			$this->_assert_equals($xml->error, 'Invalid takeaway requested.');
		} else {
			$this->_fail();
		}
	}
	
	/**
	 * Test the API with an valid takeaway ID
	 */
	function test_valid_takeawayId()
	{
		// POST to the menu API
		$result = $this->curl->simple_post('api/menu', array('key'=>API_KEY,
									 'signature'=>sha1(API_KEY.API_SECRET.$this->takeaway),
									 'takeaway'=>$this->takeaway));
		
		// Load the XML response and surpress any warnings
		$xml = @simplexml_load_string($result);
		
		// If not valid XML
		if ($xml === FALSE)
			$this->_fail('Returned invalid XML');
		
		// If there is an error element in the response
		if (isset($xml->error)) {
			$this->_fail();
		} else {
			$this->_assert_true($xml->takeaway);
		}
	}
}
