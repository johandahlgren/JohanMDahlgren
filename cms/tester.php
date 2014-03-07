<?php
	header( "Content-Type: text/html; charset=UTF-8");
	header( 'Access-Control-Allow-Origin: *');
	import_request_variables("GPC", "");
	ob_start( 'ob_gzhandler');
	session_start();
	include_once "core.php";
	
	$entityId 		= $_REQUEST["entityId"];
	$code			= $_REQUEST["code"];
?>
<html>
	<head>
		<style type="text/css">
			* {font-family: arial;}
			form {width: 100%; max-width: 800px;}
			textarea, input[type="text"] {width: 100%;}
			textarea {height: 800px;}
			.label {width: 100%;}
		</style>
	</head>
	<body>
		Output:
		<hr />
		<?php
			if ($entityId != "")
			{
				$entity 		= getEntity($entityId);
				$id 			= $entity["id"];
				$name 			= $entity["name"]; 
				$icon 			= $entity["icon"];
				$type 			= $entity["type"];
				$parentId 		= $entity["parentId"];
				$publishDate 	= $entity["publishDate"];
				$sortOrder 		= $entity["sortOrder"];
				$nodeReference 	= $entity["nodeReference"];
				$data 			= $entity["data"];
				
				if ($code != "") {
					eval("?>" . $code);
				}
			}
		?>
		
		<hr/>
		
		<form action="tester.php" method="post">
			<label for="entityId">ID:</label>
			<input type="text" id="entityId" name="entityId" value="<?php print $_REQUEST["entityId"] ?>" /><br/>
			<label for="code">Code:</label>
			<textarea id="code" name="code"><?php print $_REQUEST["code"] ?></textarea>
			<input type="submit" value="Test" />
		</form>
	</body>
</html>