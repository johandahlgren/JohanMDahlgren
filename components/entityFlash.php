<a href="index.php?entityId=<?php print getValueFromString("DetailPage", $data) ?>" class="entityFlash" <?php if (getValueFromString("Image", $data) !="" ) { ?> style="background-image: url(<?php print getValueFromString("Image", $data) ?>);"<?php } ?>>
    <span><?php print $name ?></span>
</a>