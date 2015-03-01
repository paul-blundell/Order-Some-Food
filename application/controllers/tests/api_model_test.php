<?php
require_once(APPPATH . '/controllers/tests/Toast.php');

/**
 * Test the API model
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Api_model_test extends Toast
{
	function __construct()
	{
		parent::__construct(__FILE__);
		$this->load->model('api_model','api');
	}

	function _pre() {}

	function _post() {}
	
	function test_get_secret()
	{
		// Get the secret for the API key
		$result = $this->api->getSecret('website');
		
		// If there was a result
		if ($result)
			$this->_assert_equals($result->client_secret, 'anotherlongandsecurekey');
		else
			$this->_fail();
	}
}
