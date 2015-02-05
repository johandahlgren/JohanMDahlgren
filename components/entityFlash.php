<article class="entityFlash" style="background-image: url(<?php print getValueFromString("BackgroundImage", $data) ?>);">
	<div class="innerContainer">
		<?php if (getValueFromString("Image", $data) !="" ) { ?>
		<img src="<?php print getValueFromString("Image", $data) ?>" class="mainImage" alt="Splash image for this content" />
		<?php } ?>
		<h2>
			<?php print getValueFromString("Title", $data) ?>
		</h2>
		<?php print formatText(getValueFromString("Text", $data), true, true) ?>
		<div class="center">
			<?php if (getValueFromString("Link", $data) != null && getValueFromString("LinkTitle", $data) != null) { ?>
				<a href="index.php?entityId=<?php print getValueFromString("Link", $data) ?>" class="button">
					<span><?php print getValueFromString("LinkTitle", $data) ?></span>
				</a>
			<?php } ?>
			<?php if (getValueFromString("DetailPage", $data) != null && getValueFromString("DetailPageLinkTitle", $data) != null) { ?>
				<a href="index.php?entityId=<?php print getValueFromString("DetailPage", $data) ?>" class="button">
					<span><?php print getValueFromString("DetailPageLinkTitle", $data) ?></span>
				</a>
			<?php } ?>
		</div>
	</div>
</article>