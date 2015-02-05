<div class="latestEntity" style="background-image: url(<?php print getValueFromString("BackgroundImage", $data) ?>);">
	<!--
	<span class="latestNewsTitle">
		<?php print getValueFromString("IntroText", $data) ?>
	</span>
	-->
	<?php
		$folderId 			= getValueFromString("Folder", $data);
		$detailPage 		= getValueFromString("DetailPage", $data);
		$moreEntitiesPage	= getValueFromString("MoreEntities", $data);
		$latestEntities 	= getEntities($folderId, null , null, "DESC", "DESC", 1);

		while (list ($id, $name, $state, $icon, $type, $parentId, $publishDate, $sortOrder, $nodeReference, $code, $listCode, $data) = mysql_fetch_row ($latestEntities))
		{
			renderEntity($id);
		}
	?>
	<div class="center">
		<a href="index.php?entityId=<?php print $moreEntitiesPage ?>" class="button">More news</a>
	</div>
</div>