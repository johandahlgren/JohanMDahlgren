<div class="newsList" style="background-image: url(<?php print getValueFromString("BackgroundImage", $data) ?>); background-color: <?php print getValueFromString("BackgroundColour", $data) ?>">
	<div class="innerContainer">
		<?php if (getValueFromString("Headline", $data) != "") { ?>
			<h2><?php print getValueFromString("Headline", $data) ?></h2>
		<?php } ?>
		<div class="greyBox">
			<section class="entityList">
				<?php
					$folderId = getValueFromString("Folder", $data);
					$detailPage = getValueFromString("DetailPage", $data);
					$itemsToShow = getValueFromString("ItemsToShow", $data);
					$moreItemsPage = getValueFromString("MoreItemsPage", $data);

					$_REQUEST["detailPage"] = $detailPage;

					renderEntitiesList($folderId, null, $detailPage, "DESC", $itemsToShow);
				?>
			</section>
		</div>
		<?php
			if ($itemsToShow > 0) {
				?>
				<div class="center">
					<a href="index.php?entityId=<?php print $moreItemsPage ?>" id="moreNews" class="button">Read older news</a>
				</div>
				<?php
			}
		?>
	</div>
</div>