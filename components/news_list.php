<article>
    <div class="divider"></div><div class="date"><time datetime="<?php print getYMDM2M($publishDate) ?>"><?php print getYMDshort($publishDate) ?></time></div><div class="divider"></div>
    <h2><?php print $name ?></h2>
    <?php print formatText(getValueFromString( "Text", $data)); ?>
    <div id="comments<?php print $id ?>" class="comments">
        <?php
    ob_start();
renderEntitiesList($id, null, null, "DESC", 999);
$comments = ob_get_clean();

if ($comments == "") {
    print "No comments yet.";
} else {
    print $comments;
}
        ?>
    </div>
    <div id="toggleCommentFields<?php print $id ?>" class="toggleCommentFields" onclick="toggleCommentFields(<?php print $id ?>)">Write comment</div>
    <div id="insertComment<?php print $id ?>" class="insertComment">
        <form>
            <label for="commenterName<?php print $id ?>">Name <span class="labelDescription">(Enter your name)</span></label><input type="text" id="commenterName<?php print $id ?>" name="commenterName<?php print $id ?>"/><br/>
            <label for="commentText<?php print $id ?>">Message  <span class="labelDescription">(Enter your message)</span></label><textarea id="commentText<?php print $id ?>" name="commentText<?php print $id ?>"></textarea><br/>
            <div class="center">
                <div class="button cancelComment" onclick="toggleCommentFields(<?php print $id ?>);">Cancel</div>
                <div class="button postComment" onclick="postComment(<?php print $id ?>);">Post</div>
            </div>
        </form>
    </div>
</article>