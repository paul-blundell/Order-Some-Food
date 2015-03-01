<?php
require_once(APPPATH . '/controllers/tests/Toast.php');

/**
 * Test the Order API
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Api_order extends Toast
{
	private $order;
	
	function __construct()
	{
		parent::__construct(__FILE__);
		$this->load->library('curl');
	}

	function _pre()
	{
		// The XML string containing details of the order
		$this->order = '<order>
					<takeaway>1</takeaway>
					<address1>test</address1>
					<address2>test</address2>
					<town>test</town>
					<postcode>te573t</postcode>
					<telephone>test</telephone>
					<comments></comments>
					<delivery>2</delivery>
					<item>
						<id>12</id>
						<price>4.99</price>
						<qty>1</qty>
					</item>
					<item>
						<id>13</id>
						<price>7.99</price>
						<qty>1</qty>
					</item>
				</order>';
	}

	function _post() {}

	/**
	 * Test the API no POST data
	 */
	function test_no_data()
	{
		$result = $this->curl->simple_post('api/order', array());
		
		$xml = @simplexml_load_string($result);
		
		if ($xml === FALSE) {
			$this->_fail('Returned invalid XML');
		} else {
			$this->_assert_equals($xml->status, 'Rejected');
			$this->_assert_equals($xml->message, 'Missing required data.');
		}
	}
	
	/**
	 * Test the API with an invalid key but a correct signature
	 */
	function test_invalid_key()
	{
		$result = $this->curl->simple_post('api/order', array('key'=>'ANINVALIDKEY',
									 'signature'=>sha1(API_KEY.API_SECRET.$this->order),
									 'order'=>$this->order));

		$xml = @simplexml_load_string($result);
		
		if ($xml === FALSE) {
			$this->_fail('Returned invalid XML');
		} else {	
			$this->_assert_equals($xml->status, 'Rejected');
			$this->_assert_equals($xml->message, 'Unauthorised Access.');
		}
	}
	
	/**
	 * Test the API with an invalid signature
	 */
	function test_invalid_signature()
	{
		$result = $this->curl->simple_post('api/order', array('key'=>API_KEY,
									 'signature'=>sha1(API_KEY.API_SECRET),
									 'order'=>$this->order));

		$xml = @simplexml_load_string($result);
		
		if ($xml === FALSE) {
			$this->_fail('Returned invalid XML');
		} else {	
			$this->_assert_equals($xml->status, 'Rejected');
			$this->_assert_equals($xml->message, 'Unauthorised Access.');
		}
	}
	
	/**
	 * Test the API with no key or signature
	 */
	function test_no_key_or_signature()
	{
		$result = $this->curl->simple_post('api/order', array('order'=>$this->order));

		$xml = @simplexml_load_string($result);
		
		if ($xml === FALSE) {
			$this->_fail('Returned invalid XML');
		} else {	
			$this->_assert_equals($xml->status, 'Rejected');
			$this->_assert_equals($xml->message, 'Missing required data.');
		}
	}
	
	/**
	 * Test the API with an invalid takeaway ID
	 */
	function test_invalid_takeawayId()
	{
		$this->order = '<order>
					<takeaway>2000</takeaway>
					<address1>test</address1>
					<address2>test</address2>
					<town>test</town>
					<postcode>te573t</postcode>
					<telephone>test</telephone>
					<comments></comments>
					<delivery>2</delivery>
					<item>
						<id>12</id>
						<price>4.99</price>
						<qty>1</qty>
					</item>
					<item>
						<id>13</id>
						<price>7.99</price>
						<qty>1</qty>
					</item>
				</order>';
				
		$result = $this->curl->simple_post('api/order', array('key'=>API_KEY,
									 'signature'=>sha1(API_KEY.API_SECRET.$this->order),
									 'order'=>$this->order));
		$xml = @simplexml_load_string($result);
		
		if ($xml === FALSE) {
			$this->_fail('Returned invalid XML');
		} else {	
			$this->_assert_equals($xml->status, 'Rejected');
			$this->_assert_equals($xml->message, 'Invalid takeaway.');
		}
	}
	
	/**
	 * Test the API with missing data that is required
	 */
	function test_missing_data()
	{
		$this->order = '<order>
					<takeaway>1</takeaway>
					<item>
						<id>12</id>
						<price>4.99</price>
						<qty>1</qty>
					</item>
					<item>
						<id>13</id>
						<price>7.99</price>
						<qty>1</qty>
					</item>
				</order>';
				
		$result = $this->curl->simple_post('api/order', array('key'=>API_KEY,
									 'signature'=>sha1(API_KEY.API_SECRET.$this->order),
									 'order'=>$this->order));

		$xml = @simplexml_load_string($result);
		
		if ($xml === FALSE) {
			$this->_fail('Returned invalid XML');
		} else {	
			$this->_assert_equals($xml->status, 'Rejected');
			$this->_assert_equals($xml->message, 'Missing required data.');
		}
	}
	
	/**
	 * Test the API with no items in the order
	 */
	function test_no_items()
	{
		$this->order = '<order>
					<takeaway>1</takeaway>
					<address1>test</address1>
					<address2>test</address2>
					<town>test</town>
					<postcode>te573t</postcode>
					<telephone>test</telephone>
					<comments></comments>
					<delivery>2</delivery>
				</order>';
				
		$result = $this->curl->simple_post('api/order', array('key'=>API_KEY,
									 'signature'=>sha1(API_KEY.API_SECRET.$this->order),
									 'order'=>$this->order));

		$xml = @simplexml_load_string($result);
		
		if ($xml === FALSE) {
			$this->_fail('Returned invalid XML');
		} else {	
			$this->_assert_equals($xml->status, 'Rejected');
			$this->_assert_equals($xml->message, 'No items in the order.');
		}
	}
}
