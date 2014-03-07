<?php
	header( "Content-Type: text/html; charset=UTF-8");
	header( 'Access-Control-Allow-Origin: *');
	import_request_variables("GPC", "");
	ob_start( 'ob_gzhandler');
	session_start();

	$entityId = 0;

	include "core.php";
    include "security.php";
?>

<html>
	<head>
		<title>
			Johan CMS
		</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" />
		 <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
        <meta name="mobile-web-app-capable" content="yes">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script type="text/javascript" src="js/main.js"></script>
	</head>
	<body>
		<div id="overlay"></div>
		<div id="editFormContainer">
		</div>
		<div id="nodeTree">
			<?php
				if ($_REQUEST["userAction"] == "create" || $_REQUEST["userAction"] == "update" || $_REQUEST["userAction"] == "edit")
				{
					include("formHandler.php");
				}
				renderChildren(0, true);
			?>
		</div>
		<div class="addMenuContainer">
			<div class="addMenu">
				<?php renderChildren(201, false) ?>
			</div>
		</div>
		<div id="loading"></div>
		<div id="mainMenu">
			<div class="center">
				<div id="menuEdit" class="mainMenuButton active">Edit</div>
				<div id="menuPreview" class="mainMenuButton">Preview</div>
				<div id="menuReorder" class="mainMenuButton">Reorder</div>
				<div id="menuReload" class="mainMenuButton">Reload</div>
			</div>
		</div>
	</body>
</html>