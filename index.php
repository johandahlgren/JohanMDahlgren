<?php
	header( "Content-Type: text/html; charset=UTF-8");
	header( 'Access-Control-Allow-Origin: *');
	import_request_variables("GPC", "");
	session_start();
	include_once "cms/core.php";
	
	$entityId = $_REQUEST["entityId"];

	if ($entityId == "") {
		$entityId = 116; // Root node of my site	
	}
	
	renderEntity($entityId);
?>