<div class="articleInnerContainer">
	<?php if (getValueFromString("DisplayTitle", $data) != 435) { ?>
	<h3 class="testimonialHeader"><?php print $name ?></h3>
	<?php } ?>
	<blockquote>
		<?php print getValueFromString("Text", $data) ?>
		<a href="<?php print getValueFromString("SourceLink", $data) ?>" class="source">&mdash; <?php print getValueFromString("Source", $data) ?></a>
	</blockquote>
</div>