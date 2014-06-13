<div id="entity_<?php print $id ?>" class="item">
    <div class="itemTextContainer">
        <?php if (getValueFromString("Image", $data) != "") { ?>
			<a href="<?php print getValueFromString("Image", $data) ?>" class="colorbox" title="<?php print getValueFromString("ImageText", $data) ?>">
				<span class="newsImage" style="background-image: url(<?php print getValueFromString("Image", $data) ?>);" ></span>
			</a>
		<?php } ?>
        <h3>
            <span class="shortDate"><?php print getYMDshort($publishDate) ?></span>
            <?php print $name ?>
        </h3>
        <p>
            <?php print formatText(getValueFromString( "Text", $data)); ?>
        </p>
        <!--
        <div class="contentFooter">
            <a href="index.php?entityId=<?php print $aDetailPage?>&amp;subEntityId=<?php print $id ?>" class="readMore">Read&nbsp;more&nbsp;&raquo;</a>
            <span class="discussionCounter" data-id="<?php print $id ?>"></span>
        </div>
        -->
    </div>
</div>