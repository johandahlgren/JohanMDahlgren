<?php
	//----------------
	// DB connection
	//---------------

	$db = mysql_connect("localhost", "dahlgren", "ngw0bvxj");
	mysql_select_db("dahlgren_db", $db);

	//----------------
	// Login / logout
	//----------------

	if ($_REQUEST["login"] == "true")
	{
		header("Location: login.php");
	}
	else if ($_REQUEST["userAction"] == "login")
	{
		$sqlQuery 		= "SELECT password FROM j3_user WHERE user_name = '" . $_REQUEST["cmsUserId"] . "';";
		$result 		= dbGetSingleRow($sqlQuery);
		$passwordFromDb = $result[0];

		if ($passwordFromDb == $_REQUEST["cmsPassword"])
		{
			$_SESSION["userIsLoggedIn"] = true;
		}
	}
	else if ($_REQUEST["userAction"] == "logout")
	{
		$_SESSION["userIsLoggedIn"] = false;
	}

	//---------------------
	// Set current state
	//---------------------

	$currentState = "active";

	if ($_REQUEST["state"] != null) {
		$_SESSION["state"] = $_REQUEST["state"];
	}

	if ($_SESSION["state"] != null) {
		$currentState = $_SESSION["state"];
	}

	if (strpos($_SERVER["PHP_SELF"], "admin.php") !== false) {
		$currentState = "working";
	}

	if ($currentState == "working") {
		$currentStateString = "'working', 'active'";
	} else {
		$currentStateString = "'active'";
	}

	if ($currentState != "active" && strpos($_SERVER["PHP_SELF"], "admin.php") === false) {
		?>
			<div class="state">
				State: <?php print $currentState ?>
				<a class="changeState" href="<?php print $_SERVER["PHP_SELF"] ?>?entityId=<?php print $_REQUEST["entityId"] ?>&state=active">Active</a>
			</div>
		<?php
	}

	//--------------------
	// CRUD operations
	//--------------------

	if ($_REQUEST["userAction"] == "create")
	{
		$return = createEntity();
		$entityId = $return["response"]["entityId"];
		$_SESSION["entityId"] = $entityId;
		$_REQUEST["userAction"] = "update";
	}
	else if ($_REQUEST["userAction"] == "update")
	{
		saveEntity();
		$_REQUEST["userAction"] = "";
	}
	else if ($_REQUEST["userAction"] == "delete")
	{
		deleteEntity();
		$_REQUEST["userAction"] = "";
	}
	else if ($_REQUEST["userAction"] == "reorder")
	{
		reorderEntitites($_REQUEST["activeNode"], $_REQUEST["direction"]);
		$_REQUEST["userAction"] = "";
	}
	else if ($_REQUEST["userAction"] == "changeState")
	{
		changeState($_REQUEST["activeNode"], $_REQUEST["newState"]);
		$_REQUEST["userAction"] = "";
	}

	//--------------------
	// Database functions
	//--------------------

	function dbGetSingleRow($aSqlQuery)
	{
		try
		{
			$temp 	= mysql_query($aSqlQuery);
			$result = null;

			if ($temp != null)
			{
				if (mysql_num_rows($temp) > 0)
				{
					$result = mysql_fetch_array(mysql_query($aSqlQuery)) or die("An error occured when executing the query: " . $aSqlQuery . " " . mysql_error());
				}
			}
			return $result;
		}
		catch (Exception $e)
		{
		    print "Error: " .  $e -> getMessage() . "<br/>";
		}
	}

	function dbGetMultipleRows($aSqlQuery)
	{
		try
		{
			$result = mysql_query($aSqlQuery) or die("An error occured when executing the query: ".$aSqlQuery . " " . mysql_error());
			return $result;
		}
		catch (Exception $e)
		{
		    print "Error: " .  $e -> getMessage() . "<br/>";
		}
	}

	function dbExecuteQuery($aSqlQuery)
	{
		mysql_query($aSqlQuery) or die("An error occured when executing the query: ".$aSqlQuery . " " . mysql_error());
	}

	//----------------------
	// Utility methods
	//----------------------

	function cropText($aString, $aLength)
	{
		$returnString = $aString;
		if (strlen($aString) > $aLength) {
			$returnString = substr($aString, 0, $aLength) . "&hellip;";
		}
		return $returnString;
	}

	function getFileContent($aFileName, $aPrintError)
	{
		$fileHandle		= fopen($aFileName, 'r');

		if ($fileHandle == null)
		{
			if ($aPrintError == true)
			{
				renderErrorMessage("File not found: \"" . $aFileName . "\"");
			}
			return null;
		}
		else
		{
			$fileSize 		= filesize($aFileName);
			$fileContent	= fread($fileHandle, $fileSize);
			fclose($fileHandle);

			return $fileContent;
		}
	}

	function getValueFromString($aFieldName, $aValueString, $aUnescape = false)
	{
		$unescapedDataValue		= $aValueString;
		$values 				= unserialize($unescapedDataValue);
		$value 					= getValue($aFieldName, $values);
		if ($aUnescape) {
			$value = html_entity_decode($value);
		}

		$value 					= stripslashes($value);

		return $value;
	}

	function getValue($aFieldName, $aValues)
	{
		$fieldValue = $aValues["data_" . $aFieldName];
		if ($fieldValue == null || $fieldValue == "" || $fieldValue == "NaN" || $fieldValue == "undefined")
		{
			$fieldValue = "";
		}
		return trim($fieldValue);
	}

	function requestToDataArray()
	{
		$dataArray 	= array();

		foreach($_REQUEST as $key => $value)
		{
			if (strpos($key, "data_") === 0)
			{
				$dataArray[$key] = htmlspecialchars($value, ENT_QUOTES);
			}
		}

		$dataString	= serialize($dataArray);

		return $dataString;
	}

	function getCurrentEntity()
	{
		$currentEntity = $_REQUEST["entityId"];

		if ($currentEntity == null || $currentEntity == "")
		{
			$currentEntity = 0;
		}

		return $currentEntity;
	}

	function userIsLoggedIn()
	{
		return $_SESSION["userIsLoggedIn"];
	}

    function getYMDM2M($aDate)
    {
        setlocale(LC_TIME, "en_UK");

        $dateString = strftime("%Y-%m-%d", $aDate);

        return iconv("ISO-8859-1", "UTF-8", $dateString);
    }

	function getYMD($aDate)
	{
		setlocale(LC_TIME, "en_UK");
		$todaysYear 	= date("Y");
		$yearOfEntry 	= date("Y", $aDate);

		if ($yearOfEntry < $todaysYear)
		{
			$dateString = strftime("%A %B %e %Y", $aDate);
		}
		else
		{
			$dateString = strftime("%A %B %e", $aDate);
		}

		return iconv("ISO-8859-1", "UTF-8", $dateString);
	}

	function getYMDShort($aDate)
	{
		setlocale(LC_TIME, "en_UK");
		$todaysYear 	= date("Y");
		$yearOfEntry 	= date("Y", $aDate);

		if ($yearOfEntry < $todaysYear)
		{
			$dateString = strftime("%b %e %Y", $aDate);
		}
		else
		{
			$dateString = strftime("%b %e", $aDate);
		}

		return iconv("ISO-8859-1", "UTF-8", $dateString);
	}

	function getYMDH($aDate)
	{
		setlocale(LC_TIME, "en_UK");
		$todaysYear 	= date("Y");
		$yearOfEntry 	= date("Y", $aDate);

		if ($yearOfEntry < $todaysYear)
		{
			$dateString = strftime("%A %e %B %Y %R", $aDate);
		}
		else
		{
			$dateString = strftime("%A %e %B %R", $aDate);
		}

		return iconv("ISO-8859-1", "UTF-8", $dateString);
	}

	function getYMDHShort($aDate)
	{
		setlocale(LC_TIME, "en_UK");
		$todaysYear 	= date("Y");
		$yearOfEntry 	= date("Y", $aDate);

		if ($yearOfEntry < $todaysYear)
		{
			$dateString = strftime("%a %e %b %Y %R", $aDate);
		}
		else
		{
			$dateString = strftime("%a %e %b %R", $aDate);
		}

		return iconv("ISO-8859-1", "UTF-8", $dateString);
	}

	function getEntityName($aEntityId)
	{
		$sqlQuery 	= "SELECT name FROM j4_entity WHERE id = " . $aEntityId . ";";
		$result 	= dbGetSingleRow($sqlQuery);
		return $result[0];
	}

	function getTypeIcon($aTypeId)
	{
		$sqlQuery 	= "SELECT icon FROM j4_entity WHERE id = " . $aTypeId . ";";
		$result 	= dbGetSingleRow($sqlQuery);
		return $result[0];
	}

	function getTypeById($aEntityId)
	{
		$sqlQuery 	= "SELECT type FROM j4_entity WHERE id = " . $aEntityId . ";";
		$result 	= dbGetSingleRow($sqlQuery);
		return $result[0];
	}

	function formatText($aText, $aWrapInParagraph = true, $insertParagraphs = true) {
		$fixedString = $aText;
		if ($insertParagraphs) {
			$fixedString = str_replace("\n", "</p><p>", $fixedString);
			$fixedString = str_replace("\r\n", "</p><p>", $fixedString);
		}
		$fixedString = str_replace("##", "<span class='textDivider'></span>", $fixedString);
		$fixedString = str_replace("[i]", "<i>", $fixedString);
		$fixedString = str_replace("[/i]", "</i>", $fixedString);
		$fixedString = str_replace("[q]", "<blockquote>", $fixedString);
		$fixedString = str_replace("[/q]", "</blockquote>", $fixedString);
		$fixedString = str_replace("[br]", "<br/>", $fixedString);

		$fixedString = htmlspecialchars_decode($fixedString);

		if ($aWrapInParagraph) {
			$fixedString = "<p>" . $fixedString . "</p>";
		}

		return $fixedString;
	}

	function addDiscussionWidget() {
		?>
			    <div id="disqus_thread"></div>
				<script type="text/javascript">
					/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
					var disqus_shortname = 'johanmdahlgren'; // required: replace example with your forum shortname

					/* * * DON'T EDIT BELOW THIS LINE * * */
					(function() {
						var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
						dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
						(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
					})();
				</script>
				<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
				<a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
		<?php
	}

	function getCurrentUrl() {
		return "http://" . $_SERVER[HTTP_HOST] . htmlspecialchars($_SERVER[REQUEST_URI]);
	}

	function generateSharingLinks() {
		$currentUrl = getCurrentUrl();
		$twitterLink = "https://twitter.com/intent/tweet?text=".urlencode($name) . "&url=" . urlencode($currentUrl) . "&source=johanmdahlgren.com&via=" . urlencode("Johan M. Dahlgren");
		$facebookLink = "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($currentUrl) . "&t=" . urlencode($name);
		?>
		<div class="sharingDiv">
			<script>(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
				fjs.parentNode.insertBefore(js, fjs);
				}(document, "script", "facebook-jssdk"));
			</script>
			<div class="fb-share-button" data-href="<?php $currentUrl ?>" data-type="button_count"></div>

			<!-- Place this tag where you want the +1 button to render. -->
			<div class="g-plusone" data-size="medium" data-href="<?php print currentUrl ?>"></div>

			<!-- Place this tag after the last +1 button tag. -->
			<script type="text/javascript">
				window.___gcfg = {lang: "en-GB"};
				(function() {
					var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
					po.src = "https://apis.google.com/js/platform.js";
					var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
				})();
			</script>

			<a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
		</div>
		<?php
	}

	//----------------------
	// Render methods
	//----------------------

	function renderComponent($aComponentName)
	{
		if ($aComponentName != null && $aComponentName != "")
		{
			$componentCode = getFileContent("components/" . $aComponentName . ".php", true);
			if ($componentCode != null)
			{
				eval($componentCode);
			}
		}
		else
		{
			print "<p style=\"color: darkred;\">No component name provided!</p>";
		}
	}

	function renderForm($aEntityName)
	{
		if ($aEntityName != null && $aEntityName != "")
		{
			$formCode = getFileContent("forms/" . $aEntityName . "_form.php", true);
			if ($formCode != null)
			{
				eval($formCode);
			}
		}
		else
		{
			print "<p style=\"color: darkred;\">No form name provided!</p>";
		}
	}

	function includeComponent($aComponentName)
	{
		include("components/" . $aComponentName . ".php");
	}

	//----------------------
	// Entity methods
	//----------------------

	function getEntity($aEntityId)
	{
		$sqlQuery 	= "SELECT id, name, state, icon, type, parentId, UNIX_TIMESTAMP(publishDate) as publishDate, sortOrder, nodeReference, code, listCode, data FROM j4_entity WHERE id = " . $aEntityId . ";";
		$result 	= dbGetSingleRow($sqlQuery);
		return $result;
	}

	function getEntities($aParentId = 0, $aType = null , $aCategory = null, $aSortOrder1 = ASC, $aSortOrder2 = ASC, $aNumberOfItems = 0)
	{
		$notFirstCriteria = false;

		if ($aCategory != null)
		{
			$sqlQuery 	= "SELECT ent.id, ent.name, ent.state, ent.icon, ent.type, ent.parentId, UNIX_TIMESTAMP(ent.publishDate) as publishDate, ent.sortOrder, nodeReference, ent.code, ent.listCode, ent.data, entCat.categoryId FROM j4_entity ent LEFT JOIN j4_entityCategory entCat ON entCat.entityId = ent.id WHERE entCat.categoryId = " . $aCategory . " AND";
		}
		else
		{
			$sqlQuery 	= "SELECT ent.id, ent.name, ent.state, ent.icon, ent.type, ent.parentId, UNIX_TIMESTAMP(ent.publishDate) as publishDate, ent.sortOrder, nodeReference, ent.code, ent.listCode, ent.data FROM j4_entity ent WHERE";
		}

		if ($aParentId != null || $aParentId == 0)
		{
			$sqlQuery = $sqlQuery . " ent.parentId = " . $aParentId;
			$notFirstCriteria = true;
		}

		if ($aType != null)
		{
			if($notFirstCriteria == true)
			{
				$sqlQuery = $sqlQuery . " AND";
			}
			$sqlQuery = $sqlQuery . " ent.type = '" . $aType . "'";
			$notFirstCriteria = true;
		}

		$sqlQuery 	= $sqlQuery . " AND state IN (" . $GLOBALS["currentStateString"] . ") ORDER BY sortOrder " . $aSortOrder1 . ", id " . $aSortOrder2;

		if ($aNumberOfItems > 0) {
			$sqlQuery 	= $sqlQuery . " LIMIT " . $aNumberOfItems;
		}

		$sqlQuery	= $sqlQuery . ";";
		$result 	= dbGetMultipleRows($sqlQuery);

		return $result;
	}

	function getEntitiesByType ($aType)
	{
		$sqlQuery 	= "SELECT ent.id, ent.name, ent.icon, ent.type, ent.parentId, UNIX_TIMESTAMP(ent.publishDate), ent.sortOrder, nodeReference, ent.code, ent.listCode, ent.data FROM j4_entity ent WHERE type = '" . $aType . "' AND state IN (" .  $GLOBALS["currentStateString"] . ");";
		$result 	= dbGetMultipleRows($sqlQuery);
		return $result;
	}

	function getCategoryName($aCategoryId)
	{
		$sqlQuery 	= "SELECT name FROM j4_category WHERE id = " . $aCategoryId . ";";
		$result 	= dbGetSingleRow($sqlQuery);
		return $result[0];
	}

	function getUsedTags($aSiteId)
	{
		$sqlQuery 	= "SELECT cat.id, cat.name, COUNT(cat.id) AS count FROM j3_category cat, j4_entityCategory ec, j4_entity e WHERE ec.categoryId = cat.id AND ec.entityId = e.id AND GROUP BY cat.name ORDER BY count DESC;";
		$result 	= dbGetMultipleRows($sqlQuery);
		return $result;
	}

	function getAllTags($aSiteId)
	{
		$sqlQuery 	= "SELECT cat.id, cat.name FROM j4_category cat WHERE ORDER BY name ASC;";
		$result 	= dbGetMultipleRows($sqlQuery);
		return $result;
	}

	function countChildren($aParentId)
	{
		$sqlQuery 	= "SELECT COUNT(*) FROM j4_entity e1 LEFT JOIN j4_entity e2 ON e2.parentId = e1.id WHERE e2.parentId = " . $aParentId . " GROUP BY e1.id;";
		$result 	= dbGetSingleRow($sqlQuery);
		return $result[0];
	}

	//----------------------
	// Persistence methods
	//----------------------

	function createEntity()
	{
		$parentId = $_REQUEST["parentId"];

		if ($parentId == null)
		{
			$parentId = 0;
		}

		$type = $_REQUEST["type"];

		if ($type == null)
		{
			$type = "default";
		}

		$dataString = requestToDataArray();

		$sqlQuery 	= "INSERT INTO j4_entity (name, state, icon, type, parentId, sortOrder, publishDate, code, listCode, data) VALUES('" . htmlspecialchars($_REQUEST["name"], ENT_QUOTES) . "', '" . $_REQUEST["state"] . "', '" . $_REQUEST["icon"] . "', '" . $type . "', " . $parentId . ", 0, NOW(), '" .  $_REQUEST["code"] . "', '" .  $_REQUEST["listCode"] . "', '" . $dataString . "');";
		dbExecuteQuery($sqlQuery);

		$sqlQuery2 		= "SELECT MAX(id) FROM j4_entity;";
		$result 		= dbGetSingleRow($sqlQuery2);
		$newEntityId	= $result[0];

		saveCategories($newEntityId, $dataString);

		$dataArray 					= array();
		$responseArray 				= array();
		$responseArray["code"]		= "0";
		$responseArray["message"] 	= "Entity created successfully";
		$responseArray["entityId"]	= $newEntityId;
		$dataArray["response"] 		= $responseArray;

		return $dataArray;
	}

	function saveEntity()
	{
		$entityId = $_REQUEST["entityId"];

		$dataString = requestToDataArray();
		$sqlQuery 	= "UPDATE j4_entity SET name = '" . htmlspecialchars($_REQUEST["name"], ENT_QUOTES) . "', state = '" . $_REQUEST["state"] . "', icon = '" . $_REQUEST["icon"] . "', data = '" . $dataString . "', sortOrder = " . $_REQUEST["sortOrder"] . ", parentId = " . $_REQUEST["parentId"] . ", type = '" . $_REQUEST["type"] . "', nodeReference = '" . $_REQUEST["nodeReference"] . "', code = '" .  $_REQUEST["code"] . "', listCode = '" .  $_REQUEST["listCode"] . "' WHERE id = " . $entityId . ";";
		dbExecuteQuery($sqlQuery);

		saveCategories($entityId, $dataString);

		$dataArray 					= array();
		$responseArray 				= array();
		$responseArray["code"]		= "0";
		$responseArray["message"] 	= "Save successful";
		$dataArray["response"] 		= $responseArray;

		return $dataArray;
	}

	function changeState($aEntityId, $aNewState)
	{
		$sqlQuery 	= "UPDATE j4_entity SET state = '" . $aNewState . "' WHERE id = " . $aEntityId . ";";
		dbExecuteQuery($sqlQuery);
	}

	function deleteEntity()
	{
		$entityId = $_REQUEST["entityId"];
		$sqlQuery 	= "DELETE FROM j4_entity WHERE id = " .$entityId . ";";

		dbExecuteQuery($sqlQuery);

		$dataArray 					= array();
		$responseArray 				= array();
		$responseArray["code"]		= "0";
		$responseArray["message"] 	= "Delete successful";
		$dataArray["response"] 		= $responseArray;
		return $dataArray;
	}

	function createCategory ($siteId, $aCategoryName)
	{
		$sqlQuery 	= "INSERT INTO j4_category (siteId, name) VALUES('" . $siteId . "', '" . $aCategoryName . "');";
		dbExecuteQuery($sqlQuery);

		$dataArray 					= array();
		$responseArray 				= array();
		$responseArray["code"]		= "0";
		$responseArray["message"] 	= "Category successfully created";
		$dataArray["response"] 		= $responseArray;

		return $dataArray;
	}

	function deleteCategory ($aCategoryId)
	{
		$sqlQuery 	= "DELETE FROM j4_category WHERE id = " .$aCategoryId . ";";
		dbExecuteQuery($sqlQuery);

		$dataArray 					= array();
		$responseArray 				= array();
		$responseArray["code"]		= "0";
		$responseArray["message"] 	= "Category successfully deleted";
		$dataArray["response"] 		= $responseArray;

		return $dataArray;
	}

	function addCategoryToEntity ($aCategoryId, $aEntityId)
	{
		$sqlQuery 	= "INSERT INTO j4_entityCategory (entityId, categoryId) VALUES(" . $aEntityId . ", " . $aCategoryId . ";";
		dbExecuteQuery($sqlQuery);

		$dataArray 					= array();
		$responseArray 				= array();
		$responseArray["code"]		= "0";
		$responseArray["message"] 	= "Category successfully added to the entity";
		$dataArray["response"] 		= $responseArray;

		return $dataArray;
	}

	function saveCategories($aEntityId)
	{
		$sqlQuery 	= "DELETE FROM j4_entityCategory WHERE entityId = " . $aEntityId . ";";
		dbExecuteQuery($sqlQuery);

		foreach ($_REQUEST as $key => $value)
		{
			if (stristr($key, "category_") > -1)
			{
				$categoryId = substr($key, 9);
				$sqlQuery 	= "INSERT INTO j4_entityCategory (entityId, categoryId) VALUES(" . $aEntityId . ", " . $categoryId . ");";
				dbExecuteQuery($sqlQuery);
			}
		}
	}

	function removeCategoryFromEntity ($aCategoryId, $aEntityId)
	{
		$sqlQuery 	= "DELETE FROM j4_entityCategory WHERE entity_id = " . $aEntityId . " AND category_id = " . $aCategoryId . ";";
		dbExecuteQuery($sqlQuery);

		$dataArray 					= array();
		$responseArray 				= array();
		$responseArray["code"]		= "0";
		$responseArray["message"] 	= "Category successfully removed from the entity";
		$dataArray["response"] 		= $responseArray;

		return $dataArray;
	}

	function reorderEntitites($aCurrentId, $aDirection)
	{
		$sqlQuery 		= "SELECT id, sortOrder, parentId  FROM j4_entity WHERE id = " . $aCurrentId . ";";
		$result 		= dbGetSingleRow($sqlQuery);
		$currentId 		=  $result[0];
		$currentSort 	=  $result[1];
		$currentParent 	=  $result[2];

		if($aDirection == "up")
		{
			$sqlQuery 	= "SELECT id, sortOrder FROM j4_entity WHERE sortOrder < " . $currentSort . " AND parentId = " . $currentParent . " LIMIT 1;";
			$result 	= dbGetSingleRow($sqlQuery);
			$prevId 	=  $result[0];
			$prevSort 	=  $result[1];

			if ($prevId != null)
			{
				$sqlQuery 	= "UPDATE j4_entity SET sortOrder = " . $currentSort. " WHERE id = " . $prevId . ";";
				dbExecuteQuery($sqlQuery);

				$sqlQuery 	= "UPDATE j4_entity SET sortOrder = " . $prevSort . " WHERE id = " . $currentId . ";";
				dbExecuteQuery($sqlQuery);
			}
		}
		else if ($aDirection == "down")
		{
			$sqlQuery 	= "SELECT id, sortOrder FROM j4_entity WHERE sortOrder > " . $currentSort . " AND parentId = " . $currentParent . " LIMIT 1;";
			$result 	= dbGetSingleRow($sqlQuery);
			$nextId		=  $result[0];
			$nextSort 	=  $result[1];

			if ($nextId != null)
			{
				$sqlQuery 	= "UPDATE j4_entity SET sortOrder = " . $currentSort. " WHERE id = " . $nextId . ";";
				dbExecuteQuery($sqlQuery);

				$sqlQuery 	= "UPDATE j4_entity SET sortOrder = " . $nextSort . " WHERE id = " . $currentId . ";";
				dbExecuteQuery($sqlQuery);
			}
		}

	}

	function renderChildren($aParentId, $aRenderFullMenu)
	{
		$result = getEntities($aParentId);

		while (list ($id, $name, $state, $icon, $type, $parentId, $publishDate, $sortOrder, $nodeReference, $code, $listCode, $data) = mysql_fetch_row ($result))
		{
			if ($icon == "")
			{
				$icon = getTypeIcon($type);
			}
			if ($icon == "")
			{
				$typeEntity	= getEntity($type);
				$parentType = $typeEntity["type"];
				$icon 		= getTypeIcon($parentType);
			}
			?>
				<ul class="menuUl">
					<li>
						<div class="menuItem <?php print getEntityName($type) ?> <?php print $state ?>" data-entityid="<?php print $id ?>" data-parentid="<?php print $parentId ?>" data-entityname="<?php print $name ?>" data-type="<?php print $type ?>">
							<div class="menuItemIcon" style="background-image: url('<?php print $icon ?>');" title="Toggle this node">
								<?php
									if (countChildren($id) > 0 )
									{
										print "<div class='expand'></div>";
									}
									else
									{
										print "<div class='spacer'></div>";
									}
								?>
							</div>
							<?php if ($aRenderFullMenu ) { ?>
								<div class="menuItemTitle">
									<a class="editEntity" href="#" title="Edit this node (ID: <?php print $id ?>)">
										<?php
											if ($name != "")
											{
												print $name;
											}
											else
											{
												print "Name not set";
											}
										?>
									</a>
								</div>
								<?php if ($nodeReference != "")
									{
										?>
											<div class="nodeReferences"><?php print $nodeReference; ?></div>
										<?php
									}
								?>
								<div class="previewContainer">
									<a href="../index.php?entityId=<?php print $id ?>" class="rowButton preview" title="Preview this node" >&raquo;</a>
								</div>
								<div class="reorderContainer">
									<?php if ($parentId != 0) { ?>
										<div class="rowButton moveDown" title="Move down" data-direction="down">&#8595;</div>
										<div class="rowButton moveUp" title="Move up" data-direction="up">&#8593;</div>
									<?php } ?>
								</div>
								<div class="buttonContainer">
									<?php
										if ($state == "working") {
											?>
												<a class="rowButton publishButton" href="admin.php?userAction=changeState&newState=active&activeNode=<?php print $id ?>"></a>
											<?php
										} else {
											?>
												<a class="rowButton unpublishButton" href="admin.php?userAction=changeState&newState=working&activeNode=<?php print $id ?>"></a>
											<?php
										}
									?>
									<?php if ($parentId != 0) { ?>
									<a href="#" class="rowButton deleteButton" title="Delete this node">&#10005;</a>
									<?php } ?>
									<div class="rowButton addButton" title="Add child below this node">+</div>
								</div>
								<div class="connectIcon"></div>
							<?php } else { ?>
								<div class="menuItemTitle">
									<div class="selectEntity"><?php print $name ?></div>
								</div>
							<?php } ?>
						</div>
						<?php
							renderChildren($id, $aRenderFullMenu);
						?>
					</li>
				</ul>
			<?php
		}
	}

	function renderEntity ($aEntityId) {
		if ($aEntityId != "") {
			$entity 		= getEntity($aEntityId);
			$id 			= $entity["id"];
			$name 			= $entity["name"];
			$icon 			= $entity["icon"];
			$type 			= $entity["type"];
			$parentId 		= $entity["parentId"];
			$publishDate 	= $entity["publishDate"];
			$sortOrder 		= $entity["sortOrder"];
			$nodeReference 	= $entity["nodeReference"];
			$data 			= $entity["data"];

			$typeEntity		= getEntity($type);
			$code 			= $typeEntity["code"];

			if ($code != "") {
				eval("?>" . $code);
			}
			else {
				print "No code for this node.";
			}

		}
	}

	function renderEntities($aParentId, $aType = null) {
		$entities = getEntities($aParentId, $aType);
		while (list ($id, $name, $state, $icon, $type, $parentId, $publishDate, $sortOrder, $nodeReference, $code, $listCode, $data) = mysql_fetch_row ($entities))
		{
			if ($type != 143) {
				$typeEntity = getEntity($type);
				$code = $typeEntity["code"];
				if ($code != "") {
					eval ("?>" . $code);
				}
			}
		}
	}

	function renderEntitiesList($aParentId, $aType = null, $aDetailPage, $aSortOrder = null, $aNumberOfItems = 0) {
		$entities = getEntities($aParentId, $aType, null, null, $aSortOrder, $aNumberOfItems);

		while (list ($id, $name, $state, $icon, $type, $parentId, $publishDate, $sortOrder, $nodeReference, $code, $listCode, $data) = mysql_fetch_row ($entities))
		{
			if ($type != 143) {
				$typeEntity = getEntity($type);
				$listCode = $typeEntity["listCode"];
				if ($listCode != "") {
					eval ("?>" . $listCode);
				}
			}
		}
	}

	function insertComments($id) {
		?>
			<div id="comments<?php print $id ?>" class="comments">
				<?php
					ob_start();
					renderEntitiesList($id, null, null, "DESC", 999);
					$comments = ob_get_clean();

					if ($comments == "") {
						print "No comments yet.";
					} else {
						print $comments;
					}
				?>
				<div id="toggleCommentFields<?php print $id ?>" class="toggleCommentFields" onclick="toggleCommentFields(<?php print $id ?>)">Write comment</div>
				<div id="insertComment<?php print $id ?>" class="insertComment">
					<form>
						<label for="commenterName<?php print $id ?>">Name <span class="labelDescription">(Enter your name)</span></label><input type="text" id="commenterName<?php print $id ?>" name="commenterName<?php print $id ?>"/><br/>
						<label for="commentText<?php print $id ?>">Message  <span class="labelDescription">(Enter your message)</span></label><textarea id="commentText<?php print $id ?>" name="commentText<?php print $id ?>"></textarea><br/>
						<div class="center">
							<div class="button cancelComment" onclick="toggleCommentFields(<?php print $id ?>);">Cancel</div>
							<div class="button postComment" onclick="postComment(<?php print $id ?>);">Post</div>
						</div>
					</form>
				</div>
                <a href="index.php?entityId=<?php print $_REQUEST["detailPage"] ?>&subEntityId=<?php print $id ?>" class="readMore">More</a>
			</div>
		<?php
	}
?>