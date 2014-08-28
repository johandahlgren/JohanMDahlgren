<div class="block">
    <div class="divider"></div><div class="date"><?php print getYMDshort($publishDate) ?></div><div class="divider"></div>
    <h2><?php print $name ?></h2>
    <p>
        <?php print formatText(getValueFromString( "Text", $data)); ?>
    </p>
</div>