<h1><?php print formatText($name, false, false) ?></h1>
<?php
	if (getValueFromString("Display", $data) == 290) {
		?>
			<div class="narrowContainer">
				<div class="narrow">
		<?php
	}
?>
<?php if (getValueFromString("Image", $data) != "") { ?>
	<img src="<?php print getValueFromString("Image", $data) ?>" class="articleImage" alt="Article main image" />
<?php } ?>
<?php print formatText(getValueFromString("Text", $data)) ?>
<?php
	if (getValueFromString("Display", $data) == 290) {
		?>
				</div>
			</div>
		<?php
	}
?>