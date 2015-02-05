<a href="index.php?entityId=<?php print $_REQUEST["detailPage"] ?>&&amp;subEntityId=<?php print $id ?>" class="newsLink">
	<span class="date"><time datetime="<?php print getYMDM2M($publishDate) ?>" itemprop="dateCreated"><?php print getYMDshort($publishDate) ?></time></span>
	<span class="newsItem"><?php print $name ?></span>
</a>