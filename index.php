<?php
    $time       = microtime();
    $time       = explode(' ', $time);
    $time       = $time[1] + $time[0];
    $timerStart = $time;

	header( "Content-Type: text/html; charset=UTF-8");
	header( 'Access-Control-Allow-Origin: *');
	import_request_variables("GPC", "");
	session_start();
	include_once "cms/core.php";

	$entityId = $_REQUEST["entityId"];

	if ($entityId == "") {
		$entityId = 116; // Root node of my site
	}

    $loadedFromCache = false;

	renderEntity($entityId);

    $time = microtime();
    $time = explode(' ', $time);
    $time = $time[1] + $time[0];
    $timerEnd = $time;
?>

<!--
Time to render page: <?php print round(($timerEnd - $timerStart), 3) ?>s
Loaded from cache: <?php print $loadedFromCache ?>
-->