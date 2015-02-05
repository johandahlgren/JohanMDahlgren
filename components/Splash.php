<header class="parallaxGroup" style="height: <?php print getValueFromString("Height", $data) ?>">
	<?php if (getValueFromString("BackgroundImage4", $data) != null) { ?>
		<div class="splashBackground parallaxLayer parallaxLayerDepth4" style="background-image: url(<?php print getValueFromString("BackgroundImage4", $data) ?>);"></div>
	<?php } ?>
	<?php if (getValueFromString("BackgroundImage3", $data) != null) { ?>
		<div class="splashBackground parallaxLayer parallaxLayerDepth3" style="background-image: url(<?php print getValueFromString("BackgroundImage3", $data) ?>);"></div>
	<?php } ?>
	<?php if (getValueFromString("BackgroundImage2", $data) != null) { ?>
		<div class="splashBackground parallaxLayer parallaxLayerDepth2" style="background-image: url(<?php print getValueFromString("BackgroundImage2", $data) ?>);"></div>
	<?php } ?>
	<?php if (getValueFromString("BackgroundImage1", $data) != null) { ?>
		<div class="splashBackground parallaxLayer parallaxLayerDepth1" style="background-image: url(<?php print getValueFromString("BackgroundImage1", $data) ?>);"></div>
	<?php } ?>
	<div class="innerContainer parallaxLayer parallaxLayerDepth0">
		<h1>
			<?php print getValueFromString("Title", $data) ?>
		</h1>
		<div class="half">
			<?php print formatText(getValueFromString("Text", $data), true, true) ?>
		</div>
		<div class="half">
			<?php renderEntity(getValueFromString("Media", $data)) ?>
		</div>
	</div>
</header>