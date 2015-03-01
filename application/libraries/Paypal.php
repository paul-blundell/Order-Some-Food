<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 *  This class is a concrete implementation of the abstract payment class.
 *  This class uses Express Checkout NVP v58.0
 *
 *  @author Paul Blundell
 *  @version 0.1
 *  @date November 2010
 */

class Paypal {

	private $username;
	private $password;
	private $signature;
	private $returnUrl;
	private $cancelUrl;
	private $url;
	private $data;
	
	private $ci;
		
        public function __construct()
        {
                $this->ci = & get_instance();
		$this->ci->load->library('session');
        }

	public function init($username, $password, $signature, $return, $cancel, $url) {

		$this->username = $username;
		$this->password = $password;
		$this->signature = $signature;
		$this->returnUrl = $return;
		$this->cancelUrl = $cancel;
		$this->url = $url;
		
	}
		
	
	/**
	 * Get the payment button
	 *
	 * @param double $amountToCharge formatted in pence
	 */
	public function generateButton($orderAmount) {
		
		//$button = '<div id="paymentForm"><div id="paypalPaymentForm">';
		$button = '<input class="button" type="submit" onclick="location.href=\'https://www.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token='.$this->getToken($orderAmount).'&AMT='.$orderAmount.'&CURRENCYCODE=GBP&RETURNURL='.$this->returnUrl.'&CANCELURL='.$this->cancelUrl.'\';return false;" value="Pay with PayPal" />';
		//$button .= '<input type="image" src="https://fpdbs.paypal.com/dynamicimageweb?cmd=_dynamic-image&locale=en_GB" value="'._('Pay with PayPal').'" /></a></div></div>';

		return $button;
	}

	/*
	 * Return the initial token for a paypal transaction, the token is
	 * used as a session id, for the rest of the transaaction.
	 */
	public function getToken($amount) {
		
		$this->ci->session->set_userdata('order-amount', $amount);
	
		$data = array(
			'USER' => $this->username,
			'PWD' => $this->password,
			'SIGNATURE' => $this->signature,
			'VERSION' => 2.3,
			'PAYMENTACTION' => 'Sale',
			'RETURNURL' => $this->returnUrl,
			'CANCELURL' => $this->cancelUrl,
			'AMT'=>$amount,
			'CURRENCYCODE'=>'GBP',  
			'METHOD' => 'SetExpressCheckout'
			);

		$result = $this->parseResult($this->postRequest($this->url, $data));

		if(!is_array($result))
			return _('Couldnt get the token, an invalid result was returned');
		elseif(!array_key_exists('token', $result))
			return _('Couldnt get the token, it wasnt in the result');

		// take the token from this
		$_SESSION['token'] = $result['token'];
		return $result['token'];
	}

	/*
	 * Get User Details
	 */
	public function getUser($token) {

		$data = array(
			'USER' => $this->username,
			'PWD' => $this->password,
			'SIGNATURE' => $this->signature,
			'VERSION' => 2.3,
			'TOKEN' => $token,
			'METHOD' => 'GetExpressCheckoutDetails'
			);

		$result = $this->parseResult($this->postRequest($this->url, $data));

		if(!is_array($result))
			throw new Exception(_('Couldnt confirm payment, an invalid result was returned'));

		return $result['ack'];
	}


	/*
	 * Confirm the payment was made from paypal
	 */
	public function getConfirmation($payerId,$token,$amount) { //DoExpressCheckoutPayment

		$data = array(
			'USER' => $this->username,
			'PWD' => $this->password,
			'SIGNATURE' => $this->signature,
			'VERSION' => 2.3,
			'PAYMENTACTION' => 'Sale',
			'PAYERID' => $payerId,
			'TOKEN' => $token,
			'AMT' => $amount,
			'CURRENCYCODE' => 'GBP',
			'METHOD' => 'DoExpressCheckoutPayment' 
			);

		$result = $this->parseResult($this->postRequest($this->url, $data));

		if(!is_array($result))
			throw new Exception(_('Couldnt confirm payment, an invalid result was returned'));

		if($result['ack'] == 'Success') {
			return true;
		} else {
			return false;
		}
	}


	/*
	 * Accepts a fully qualified url and standard formatted data array
	 * keys are NOT url-encoded, only the values.
	 * returns string of output from the url
	 */
	private function postRequest($url, array $data) {

		foreach($data AS $key => $value)
			$params[] = $key.'='.urlencode($value);

		$url = $url.'?'.implode('&', $params);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		$returned = curl_exec($ch);
		curl_close($ch);
		
		

		return $returned;
	}

	/*
	 * Returns a keyed array from a string formatted like:
	 * a=b&c=d&e=f
	 */
	private function parseResult($result) {
		$array = array();
		foreach(explode('&', $result) AS $pair) {
			$pair = explode('=', $pair);
			if(count($pair) == 2)
				$array[strtolower($pair[0])] = $pair[1];
		}
		return $array;
	}
}

?>
