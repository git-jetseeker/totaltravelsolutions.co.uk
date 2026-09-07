<?php
function paypalApiRequest($method,$params = array()) {
	//Our request parameters
	$requestParams = array(
		'METHOD' => $method,
		'VERSION' => '74.0',
		'PWD' => '9ML29YLPEYPG9897',
		'USER' => 'imran-facilitator_api1.flyparkplus.co.uk',
		'SIGNATURE' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31ASk-rv0WOrgN1NJ.Y7ybvu2ay8EO'
	);
	//Building our NVP string
	$request = http_build_query($requestParams + $params);
 
	//cURL settings
	$curlOptions = array (
		CURLOPT_URL => $this -> getOption('endpoint'),
		CURLOPT_VERBOSE => 1,
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_SSL_VERIFYHOST => 2,
		CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem', //CA cert file
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => $request
	);
 
	$ch = curl_init();
	curl_setopt_array($ch,$curlOptions);
 
	//Sending our request - $response will hold the API response
	$response = curl_exec($ch);
 
	//Checking for cURL errors
	if (curl_errno($ch)) {
		//Handle errors
	} else  {
		curl_close($ch);
		$responseArray = array();
		parse_str($response,$responseArray); // Break the NVP string to an array
		return $responseArray;
	}
}


?>