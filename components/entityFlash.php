<a href="index.php?entityId=<?php print getValueFromString("DetailPage", $data) ?>">
    <div class="entityFlash" <?php if (getValueFromString("Image", $data) !="" ) { ?> style="background-image: url(<?php print getValueFromString("Image", $data) ?>);" }<?php } ?>>
        <h2><?php print $name ?></h2>
    </div>
</a>