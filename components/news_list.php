<div class="item">
    <h3>
        <span class="shortDate"><?php print getYMDshort($publishDate) ?> -</span>
        <?php print $name ?>
    </h3>
    <?php if (getValueFromString("Image", $data) !="" ) { ?>
        <span id="news-<?php print $id ?>" class="newsImage" style="background-image: url(<?php print getValueFromString("Image", $data) ?>);"></span>
    <?php } ?>
    <p>
        <?php print cropText(strip_tags(formatText(getValueFromString( "Text", $data))), 250); ?>
    </p>
    <div class="contentFooter">
        <span class="discussionCounter" data-id="<?php print $id ?>"></span>
        <a href="index.php?entityId=<?php print $aDetailPage?>&amp;subEntityId=<?php print $id ?>" class="readMore">Read&nbsp;more&nbsp;&raquo;</a>
    </div>
</div>