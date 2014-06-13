<div id="entity_<?php print $id ?>" class="item">
    <div class="itemTextContainer">
        <?php if (getValueFromString("Image", $data) != "") { ?>
			<a href="<?php print getValueFromString("Image", $data) ?>" class="colorbox" title="<?php print getValueFromString("ImageText", $data) ?>">
				<span class="newsImage" style="background-image: url(<?php print getValueFromString("Image", $data) ?>);" ></span>
			</a>
		<?php } ?>
        <h3>
            <?php print $name ?>
        </h3>
        <p>
            <?php print formatText(getValueFromString( "Text", $data)); ?>
        </p>
		<!--
        <a href="http://<?php print  $_SERVER["HTTP_HOST"] ?>/index.php?entityId=<?php print $aDetailPage?>&amp;subEntityId=<?php print $id ?>" class="newsDetail"></a>
		-->
        <!--
        <span class="discussionCounter" data-id="<?php print $id ?>"></span>
        -->
    </div>
	<div class="shortDate"><?php print getYMDshort($publishDate) ?></div>
	<div class="right">
		<div class="addThisShare addthis_toolbox addthis_default_style" addthis:url="http://<?php print  $_SERVER["HTTP_HOST"] ?>/index.php?entityId=<?php print $aDetailPage?>&amp;subEntityId=<?php print $id ?>">
			<div class="loadingIndicator"></div>
			<a class="addthis_counter addthis_bubble_style"></a>
			<a class="addthis_button_twitter"></a>
			<a class="addthis_button_google_plusone_share"></a>
			<a class="addthis_button_facebook"></a>
			Share
		</div>
	</div>
</div>