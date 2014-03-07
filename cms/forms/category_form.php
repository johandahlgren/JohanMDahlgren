$entityId = $_REQUEST["entityId"];
if ($entityId == null)
{
	$entityId = $_SESSION["entityId"];
}

if ($entityId != null)
{
	$result = getEntity($entityId);
	$name = $result["name"];
	$data = $result["data"];
	$description = getValueFromString("description", $data);
}
?>
<form action="" method="get">
	<input type="hidden" name="userAction" value="update" />
	<input type="hidden" name="type" value="<?php print $_REQUEST["type"] ?>" />
	<input type="hidden" name="entityId" value="<?php print $entityId ?>" />
	Name
	<input type="text" name="name" value="<?php print $name ?>" /><br/>
	Description
	<input type="text" name="data_description" value="<?php print getValueFromString("description", $data) ?>" /><br/>
	<input type="submit" value="cancel"/>
	<input type="submit" value="save"/>
</form>