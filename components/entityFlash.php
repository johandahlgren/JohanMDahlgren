<a href="index.php?entityId=<?php print getValueFromString("DetailPage", $data) ?>">
	<div class="entityFlash <?php print getValueFromString("ExtraClass", $data) ?>">
        <div class="entityFlashImage" style="background-image: url(<?php print getValueFromString("Image", $data) ?>);"></div>
        <h2><?php print $name ?></h2>
        <p><?php print htmlspecialchars_decode(getValueFromString("Text", $data)) ?></p>
        <span class="fakeLink">Read more &raquo;</span>
	</div>
</a>