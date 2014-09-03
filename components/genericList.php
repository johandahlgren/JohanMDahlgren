<?php if (getValueFromString("Headline", $data) != "") { ?>
<h2><?php print getValueFromString("Headline", $data) ?></h2>
<?php } ?>
<section class="entityList">
	<?php
		$folderId = getValueFromString("Folder", $data);
		$detailPage= getValueFromString("DetailPage", $data);
		$itemsToShow = getValueFromString("ItemsToShow", $data);
		$moreItemsPage = getValueFromString("MoreItemsPage", $data);
		renderEntitiesList($folderId, null, $detailPage, "DESC", $itemsToShow);

if ($itemsToShow > 0) {
?>
<a href="index.php?entityId=<?php print $moreItemsPage ?>" id="moreNews">Read older news &raquo;</a>
<?php
}
	?>
</section>
