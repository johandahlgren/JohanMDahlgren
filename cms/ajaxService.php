<?php
	header("Content-Type: text/html; charset=UTF-8");
	header("Access-Control-Allow-Origin: *");
	import_request_variables("GPC", "");
	session_start();
	include_once "core.php";

	$action 	= $_REQUEST["action"];
	$entityId 	= $_REQUEST["entityId"];
	$parentId 	= $_REQUEST["parentId"];

	// Example request: http://www.johanmdahlgren.com/cms/ajaxService.php?action=createEntity&parentId=425&type=440&name=Comment%201&state=active&data_CommenterName=Johan&data_Text=testingtesting

	if ($action == "createEntity") {
		createEntity();
		renderEntitiesList($parentId, null, 123, "DESC", 999);
	} else if ($action == "renderEntity") {
		renderEntity($entityId);
	} else if ($action == "renderEntities") {
		renderEntitiesList($parentId, null, 123, "DESC", 999);
	} else {
		print "No valid action provided. Provided: " . $action;
	}
?>