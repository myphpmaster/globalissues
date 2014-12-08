<?php

/*
*  List all functions used
*
*/

// Function to get json data
function mashape_json($service,$vars){
	require_once 'includes/Unirest.php';
	
	global $mashape_key;
	$http_vars = implode('&', array_map(function ($v, $k) { return $k . '=' . $v; }, $vars, array_keys($vars)));

	$providers = array(
			"location" => "https://montanaflynn-geocoder.p.mashape.com/ip?",
			"weather" => "https://community-open-weather-map.p.mashape.com/forecast?",
			);

	$response = Unirest::get($providers[$service] . $http_vars,
    		array(
      			"X-Mashape-Key" => $mashape_key
    			)
  		);
  
  	return $response;
}

// Function to get json data direct
function direct_json($service,$vars=false,$format='decode'){
	global $google_key, $insta_key;

	if ($vars)
		$http_vars = implode('&', array_map(function ($v, $k) { return $k . '=' . $v; }, $vars, array_keys($vars)));
	else
		$http_vars = '';

	$providers = array(
			"usgs" => "http://comcat.cr.usgs.gov/fdsnws/event/1/query?format=geojson&orderby=time&",
			"gplaces" => "https://maps.googleapis.com/maps/api/place/nearbysearch/json?key=" . $google_key . "&",
			"gpdetails" => "https://maps.googleapis.com/maps/api/place/details/json?key=" . $google_key . "&",
			"metwit" => "https://api.metwit.com/v2/weather/?",
			"openweather" => "http://api.openweathermap.org/data/2.5/weather?",
			"instagram" => "https://api.instagram.com/v1/media/search?client_id=" . $insta_key . "&",
			);

	$raw = file_get_contents($providers[$service] . $http_vars);

	if($format=='raw') {
		str_replace(array("\r\n", "\r"), "\n", $raw);
		$lines = explode("\n", $raw);
		$new_lines = array();

		foreach ($lines as $i => $line) {
    			if(!empty($line))
        		$new_lines[] = trim($line);
		}

		return implode($new_lines);

	} else if($format=='url') {
		return $providers[$service] . $http_vars;
	} else {
		return json_decode($raw);
	}
}

// Function to get xml data
function direct_xml($service,$vars=false) {

	$context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));

	if ($vars)
		$http_vars = implode('&', array_map(function ($v, $k) { return $k . '=' . $v; }, $vars, array_keys($vars)));
	else
		$http_vars = '';

	$providers = array(
			"pdcdisaster" => "http://www.pdc.org/feed.xml",
			);
	$xml = file_get_contents($providers[$service] . $http_vars, false, $context);
	$xml = preg_replace('/(<\/?\w+):(\w+\>)/i','$1$2',$xml);
	$xml = simplexml_load_string($xml);
	
	return $xml;
}


// Function to get json from Quandl
function quandl_json($service,$id,$vars=false,$format='decode'){
	global $quandl_key;
	
	if ($vars)
		$http_vars = implode('&', array_map(function ($v, $k) { return $k . '=' . $v; }, $vars, array_keys($vars)));
	else
		$http_vars = '';
		

	$urls = array(
		"gdppc" => "https://www.quandl.com/api/v1/datasets/ODA/" . $id . "_NGDPPC.json?auth_token=" . $quandl_key .'&',
		"gdpgro" => "https://www.quandl.com/api/v1/datasets/ODA/" . $id . "_NGDP_RPCH.json?auth_token=" . $quandl_key .'&',
		"cpi" => "https://www.quandl.com/api/v1/datasets/WORLDBANK/" . $id . "_GFDD_OE_02.json?auth_token=" . $quandl_key .'&',
		"pop" => "https://www.quandl.com/api/v1/datasets/WORLDBANK/" . $id . "_SP_POP_TOTL.json?auth_token=" . $quandl_key .'&',
		"unemp" => "https://www.quandl.com/api/v1/datasets/WORLDBANK/" . $id . "_SL_UEM_TOTL_ZS.json??auth_token=" . $quandl_key .'&',
			);
			
	$raw = file_get_contents($urls[$service] . $http_vars);

	if($format=='raw') {
		str_replace(array("\r\n", "\r"), "\n", $raw);
		$lines = explode("\n", $raw);
		$new_lines = array();

		foreach ($lines as $i => $line) {
    			if(!empty($line))
        		$new_lines[] = trim($line);
		}

		return implode($new_lines);

	} else if($format=='url') {
		return $providers[$service] . $http_vars;
	} else {
		return json_decode($raw);
	}
}

// Function to get Google Data C
function google_json($service,$vars=false){
	require_once 'includes/GoogleAPI/autoload.php';

	global $google_key;
	
	$client = new Google_Client();
  	$client->setDeveloperKey($google_key);
	
	switch($service){
		case 'youtube' :
			$youtube = new Google_Service_YouTube($client);
			$searchResponse = $youtube->search->listSearch('id,snippet', $vars);
		break;
	}

	return $searchResponse['items'];
}

// Function to get Yahoo WOEID
function yahoo_json($location){
	$raw = file_get_contents("https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20geo.placefinder%20where%20text%3D%22" . rawurlencode($location) . "%22&format=json");
	return json_decode($raw);
}

// Function to get Twitter JSON
function twitter_json($service,$vars){
	require_once 'includes/TwitterAPIExchange.php';
	
	global $twitter_key;

	$urls = array(
			"trending" => "https://api.twitter.com/1.1/trends/place.json",
			"closest" => "https://api.twitter.com/1.1/trends/closest.json",
			);

	$requestMethod = "GET";

	$http_vars = implode('&', array_map(function ($v, $k) { return $k . '=' . $v; }, $vars, array_keys($vars)));
	$getfield = '?' . $http_vars;

	$twitter = new TwitterAPIExchange($twitter_key);
	$string = json_decode($twitter->setGetfield($getfield)->buildOauth($urls[$service], $requestMethod)->performRequest(),$assoc = TRUE);
	
	return $string;
}

// Function to get json from Quandl using lat,lng
function wunder_json($lat,$lng){
	global $wunder_key;
	$url = "http://api.wunderground.com/api/" . $wunder_key . "/forecast/q/" . $lat . "," . $lng . ".json";
	$raw = file_get_contents($url);
	return json_decode($raw);
}

// Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

// Function to check output
function checking($var){
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}

?>