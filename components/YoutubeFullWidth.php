<article class="youTube" style="background-image: url(<?php print getValueFromString("BackgroundImage", $data) ?>);">
	<div class="innerContainer">
		<h2>
			<?php print getValueFromString("Title", $data) ?>
		</h2>
		<div class="youtubeFullWidthContainer">
			<iframe class="youtubeFullWidth" width="100%" height="100%" src="//www.youtube.com/embed/<?php print getValueFromString("VideoId", $data); ?>?vq=hd1080&autoplay=0&showinfo=0&controls=0&rel=0&modestbranding=1" frameborder="0" allowfullscreen></iframe>
		</div>
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