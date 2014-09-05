<div class="bookPresentation contentContainer">
    <h1><?php print $name ?> - A novel</h1>
    <a href="<?php print getValueFromString("Image", $data) ?>" class="swipebox" title="<?php print getValueFromString("ImageText", $data) ?>">
        <img class="bookCover" src="<?php print getValueFromString("Image", $data) ?>" alt="Book cover of <?php print $name ?>"/>
    </a>
    <?php if (getValueFromString("Quote", $data) != null) { ?>
    <blockquote><?php print getValueFromString("Quote", $data) ?></blockquote>
    <?php } ?>
    <div class="newBlock"></div>
    <?php print formatText(getValueFromString("Text", $data)) ?>
    <div class="subPageLinksContainer">
        <?php
    $subPages = getEntities($_REQUEST["entityId"], 143);
while (list ($pageId, $pageName, $pageStatus, $pageIcon, $pageType, $pageParentId, $pagePublishDate, $pageSortOrder, $pageNodeReference, $pageCode, $pageData) = mysql_fetch_row ($subPages))
{
        ?>
        <a href="index.php?entityId=<?php print $pageId ?>" class="subPageLink" style="background-image: url(<?php print $pageIcon ?>);"><?php print $pageName ?></a>
        <?php
}
        ?>
    </div>
</div>