<a href="index.php?entityId=<?php print getValueFromString("DetailPage", $data) ?>">
	<div class="entityFlash <?php print getValueFromString("ExtraClass", $data) ?>">
        <h2><?php print $name ?></h2>
        <?php if (getValueFromString("Image", $data) !="" ) { ?>
            <img src="<?php print getValueFromString("Image", $data) ?>" alt="<?php print $name ?>"/>
        <?php } ?>
        <p><?php print htmlspecialchars_decode(getValueFromString("Text", $data)) ?></p>
        <span class="fakeLink">Read more &raquo;</span>
	</div>
</a>