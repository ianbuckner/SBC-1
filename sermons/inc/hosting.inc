<?php 


function buildURL($url) {

	$host = "localhost";
	if ($_SERVER['SERVER_NAME'] != "localhost") {
		$host = "www.skiptonbaptistchurch.com";
	}
	$url = "http://" . $host . "/" . $url;	
	
	return $url;
}


?>