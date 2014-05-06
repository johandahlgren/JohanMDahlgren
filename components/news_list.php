<div class="item">
    <div class="itemTextContainer">
        <a href="index.php?entityId=<?php print $aDetailPage?>&amp;subEntityId=<?php print $id ?>">
            <h3>
                <span class="shortDate"><?php print getYMDshort($publishDate) ?></span>
                <?php print $name ?>
            </h3>
        </a>
        <p>
            <?php print cropText(strip_tags(formatText(getValueFromString( "Text", $data))), 250); ?>
        </p>
        <div class="contentFooter">
            <a href="index.php?entityId=<?php print $aDetailPage?>&amp;subEntityId=<?php print $id ?>" class="readMore">Read&nbsp;more&nbsp;&raquo;</a>
            <!--
            <span class="discussionCounter" data-id="<?php print $id ?>"></span>
            -->
        </div>
    </div>
</div>