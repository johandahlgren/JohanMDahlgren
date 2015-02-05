<article class="soundtrack">
	<div class="articleInnerContainer">
		<h1><?php print $name ?></h1>
		<div class="spotifyTracks">
			<?php print getValueFromString("Tracks", $data, true) ?>
		</div>
		<?php print formatText(getValueFromString("Text", $data)) ?>
	</div>
</article>