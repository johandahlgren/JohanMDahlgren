<?php
	$childPages = getEntities($_REQUEST["entityId"], 143);
	while (list ($id, $name, $state, $icon, $type, $parentId, $publishDate, $sortOrder, $nodeReference, $code, $listCode, $data) = mysql_fetch_row ($childPages))
	{
		$books = getEntities($id, 134);

		while (list ($bookId, $bookName, $bookState, $bookIcon, $bookType, $bookParentId, $bookPublishDate, $bookSortOrder, $bookNodeReference, $bookCode, $bookListCode, $bookData) = mysql_fetch_row ($books))
		{
			?>
				<div class="container">
					<h2><?php print $bookName ?></h2>
					<a href="index.php?entityId=<?php print $id ?>"><img class="bookCover" src=" <?php print getValueFromString("Image", $bookData) ?>" alt="Book cover of <?php print $bookName ?>" /></a>
					<p><?php print formatText(getValueFromString("Text", $bookData), false, true) ?></p>
					<div class="newBlock">
						<a href="index.php?entityId=<?php print $id ?>" class="readMore">Read more &raquo;</a>
					</div>
				</div>
			<?php
		}
	}
?>