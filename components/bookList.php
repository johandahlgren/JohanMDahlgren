<div class="bookList">
	<?php
		$childPages = getEntities($id, 143);
		while (list ($id, $name, $state, $icon, $type, $parentId, $publishDate, $sortOrder, $nodeReference, $code, $listCode, $data) = mysql_fetch_row ($childPages))
		{
			$books = getEntities($id, 134);

			while (list ($bookId, $bookName, $bookState, $bookIcon, $bookType, $bookParentId, $bookPublishDate, $bookSortOrder, $bookNodeReference, $bookCode, $bookListCode, $bookData) = mysql_fetch_row ($books))
			{
				?>
					<article>
						<div class="articleInnerContainer">
							<a href="index.php?entityId=<?php print $id ?>"><img class="bookCover" src=" <?php print getValueFromString("Image", $bookData) ?>" alt="Book cover of <?php print $bookName ?>" /></a>
							<h2><?php print $bookName ?></h2>
							<span class="novelSubTitle"> <?php print getValueFromString("SubTitle", $bookData) ?></span>
							<p><?php print formatText(getValueFromString("ShortText", $bookData), false, true) ?></p>
							<div class="marginTop">
								<a href="index.php?entityId=<?php print $id ?>" class="button">Read more</a>
							</div>
						</div>
					</article>
				<?php
			}
		}
	?>
</div>
