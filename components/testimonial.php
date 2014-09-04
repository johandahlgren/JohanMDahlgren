<?php if (getValueFromString("DisplayTitle", $data) != 435) { ?>
<h3><?php print $name ?></h3>
<?php } ?>
<blockquote>
    <?php print getValueFromString("Text", $data) ?>
    <a href="<?php print getValueFromString("SourceLink", $data) ?>" class="source"><?php print getValueFromString("Source", $data) ?></a>
</blockquote>
