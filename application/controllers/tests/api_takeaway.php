<?php
require_once(APPPATH . '/controllers/tests/Toast.php');

/**
 * Test the takeaway API
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Api_takeaway extends Toast
{
	private $location;
	
	function __construct()
	{
		parent::__construct(__FILE__);
		$this->load->library('curl');
	}

	function _pre()
	{
		// Set the default location
		$this->location = 'SY23';
	}

	function _post() {}

	/**
	 * Test the API no POST data
	 */
	function test_no_data()
	{
		$result = $this->curl->simple_post('api/takeaway', array());
		
		$xml = @simplexml_load_string($result);
		
		if ($xml === FALSE)
			$this->_fail('Returned invalid XML');
		
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
		$result = $this->curl->simple_post('api/takeaway', array('key'=>'ANINVALIDKEY',
									 'signature'=>sha1(API_KEY.API_SECRET.$this->location),
									 'location'=>$this->location));
		
		$xml = @simplexml_load_string($result);
		
		if ($xml === FALSE)
			$this->_fail('Returned invalid XML');
		
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
		// Signature not being encrypted with the location value
		$result = $this->curl->simple_post('api/takeaway', array('key'=>'ANINVALIDKEY',
									 'signature'=>sha1(API_KEY.API_SECRET),
									 'location'=>$this->location));
		
		$xml = @simplexml_load_string($result);
		
		if ($xml === FALSE)
			$this->_fail('Returned invalid XML');
		
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
		$result = $this->curl->simple_post('api/takeaway', array('location'=>$this->location));
		
		$xml = @simplexml_load_string($result);
		
		if ($xml === FALSE)
			$this->_fail('Returned invalid XML');
		
		if (isset($xml->error)) {
			$this->_assert_equals($xml->error, 'Missing required data.');
		} else {
			$this->_fail();
		}
	}
	
	/**
	 * Test the API with an invalid location
	 */
	function test_invalid_location()
	{
		$this->location = 'aninvalidlocationthatgooglewillnotfind';
		
		$result = $this->curl->simple_post('api/takeaway', array('key'=>API_KEY,
									 'signature'=>sha1(API_KEY.API_SECRET.$this->location),
									 'location'=>$this->location));
		
		$xml = @simplexml_load_string($result);
		
		if ($xml === FALSE)
			$this->_fail('Returned invalid XML');
		
		if (isset($xml->error)) {
			$this->_assert_equals($xml->error, 'Could not get location.');
		} else {
			$this->_fail();
		}
	}
	
	/**
	 * Test the API with an invalid latitude and longitude values
	 */
	function test_invalid_latlong()
	{		
		$result = $this->curl->simple_post('api/takeaway', array('key'=>API_KEY,
									 'signature'=>sha1(API_KEY.API_SECRET.''),
									 'latitude'=>'',
									 'longitude'=>''));
		
		$xml = @simplexml_load_string($result);
		
		if ($xml === FALSE)
			$this->_fail('Returned invalid XML');
		
		if (isset($xml->error)) {
			$this->_assert_equals($xml->error, 'Could not get location.');
		} else {
			$this->_fail();
		}
	}
	
	/**
	 * Test the API with an valid latitude and longitude value
	 */
	function test_valid_latlong()
	{		
		$result = $this->curl->simple_post('api/takeaway', array('key'=>API_KEY,
									 'signature'=>sha1(API_KEY.API_SECRET.'52.411793'),
									 'latitude'=>'52.411793',
									 'longitude'=>'-4.070862'));
		
		$xml = @simplexml_load_string($result);
		
		if ($xml === FALSE)
			$this->_fail('Returned invalid XML');
		
		if (isset($xml->error)) {
			$this->_fail();
		} else {
			$this->_assert_true($xml->takeaway);
		}
	}
	
	/**
	 * Test the API with an valid location
	 */
	function test_valid_location()
	{		
		$result = $this->curl->simple_post('api/takeaway', array('key'=>API_KEY,
									 'signature'=>sha1(API_KEY.API_SECRET.$this->location),
									 'location'=>$this->location));
		
		$xml = @simplexml_load_string($result);
		
		if ($xml === FALSE)
			$this->_fail('Returned invalid XML');
		
		if (isset($xml->error)) {
			$this->_fail();
		} else {
			$this->_assert_true($xml->takeaway);
		}
	}

}
