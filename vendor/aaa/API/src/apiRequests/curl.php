<?php

use PHPUnit\Runner\Exception;

define('APIKEY', 'RGAPI-ff65a9fb-760c-4182-af48-ceda642a8c31');


function curl($targetUrl, $assoc = false, $additionalParameters = null)
{
	$curl = curl_init();

	// Depending if we have additional parameters to pass we choose the right call constructor
	if (isset($additionalParameters)) {
		$params = "";
		// query with params
		foreach ($additionalParameters as $key => $value) {
			if (isset($value)) {
				$params = $params . "&$key=" . $value;
			}
		}
		$targetUrl = $targetUrl . '?api_key=' . APIKEY . $params;
	} else {
		// Normal query
		$targetUrl = $targetUrl . '?api_key=' . APIKEY;
	}
	// curl set options
	curl_setopt_array($curl, [
		// 1(true) returns the body, 0(false) returns bool value
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $targetUrl,
		// retrive headers
		CURLOPT_HEADER => 1,
		CURLOPT_VERBOSE, 1,
	]);
	//execute
	$response = curl_exec($curl);
	// curl connection info
	$info = curl_getinfo($curl);
	// Get header size from $info
	$curlHeaderSize = $info['header_size'];

	// Found on github https://gist.github.com/neopunisher/5292506. Converts string text to array
	$sBody = trim(mb_substr($response, $curlHeaderSize));
	$ResponseHeader = explode("\n", trim(mb_substr($response, 0, $curlHeaderSize)));

	// This removes the first entry. The response code
	unset($ResponseHeader[0]);
	$aHeaders = array();
	foreach ($ResponseHeader as $line) {
		list($key, $val) = explode(':', $line, 2);
		$aHeaders[strtolower($key)] = trim($val);
	}

	// add response code since it wasn't added by the above code.
	// Since the response code is not nicely formated from RIOT we will just add it from $info since we need it anyway
	$aHeaders["response_code"] = $info["http_code"];
	// Add the url for easier debugging
	// TODO: Remove?
	$aHeaders["targetUrl"] = $targetUrl;

	// TODO: Put checks in place if RIOT's API is slow/working
	if ($response === false) {
		eh("something went wrong with curl request");
	}

	//close cURL
	curl_close($curl);
	// $assoc determined whether the array is converted to an object or an assosiative array
	$data = json_decode($sBody, $assoc);

	pr($aHeaders);

	// check for response code and proceed accordingly

	if ($aHeaders["response_code"] == 200) {
		return $data;
	}
	// Check the reponse code error
	else {
		// TODO: Add stuff for different errors?
		switch ($aHeaders["response_code"]) {
				// Bad request
			case 400:
				eh("error " . $aHeaders["response_code"] . " Code");
				break;
				// Unauthorized
			case 401:
				eh("error " . $aHeaders["response_code"] . " Code");
				break;
				// Forbidden (no API key/ wrong API key)
			case 403:
				eh("error " . $aHeaders["response_code"] . " Code");
				break;
				// Data not found
			case 404:
				eh("error " . $aHeaders["response_code"] . " Code");
				break;
				// Method not allowed
			case 405:
				eh("error " . $aHeaders["response_code"] . " Code");
				break;
				// Unsupported media type
			case 415:
				eh("error " . $aHeaders["response_code"] . " Code");
				break;
				// Limit reached
			case 429:
				die("Error limit reached" . " retry-after " . $aHeaders["retry-after"] . " seconds");
				break;

				// Errors from RIOT servers
				// Internal server error
			case 500:
				eh("error " . $aHeaders["response_code"] . " Code");
				break;
				//Bad gateway
			case 502:
				eh("error " . $aHeaders["response_code"] . " Code");
				break;
				// Service unavailable
			case 503:
				eh("error " . $aHeaders["response_code"] . " Code");
				break;
				// Gateway timeout
			case 504:
				eh("error " . $aHeaders["response_code"] . " Code");
				break;
			default:
				throw new Exception("Unknown error " . $aHeaders["response_code"] . " Code");
				break;
		}
	}
}
