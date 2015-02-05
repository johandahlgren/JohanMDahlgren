<article id="entity<?php print $id ?>" class="entityFlash" style="background-image: url(<?php print getValueFromString("BackgroundImage", $data) ?>); background-color: <?php print getValueFromString("BackgroundColour", $data) ?>">
	<div class="innerContainer" itemscope itemtype="http://schema.org/Book">
		<meta itemprop="description" content="<?php print strip_tags(formatText(getValueFromString("About", $data))) ?>" />
		<meta itemprop="audience" content="<?php print getValueFromString("Audience", $data) ?>" />
		<meta itemprop="copyrightHolder" content="<?php print getValueFromString("Author", $data) ?>" />
		<meta itemprop="editor" content="<?php print getValueFromString("Editor", $data) ?>" />
		<meta itemprop="isFamilyFriendly" content="false" />
		<meta itemprop="keywords" content="<?php print getValueFromString("Keywords", $data) ?>" />
		<meta itemprop="dateCreated" content="<?php print getYMDM2M($publishDate) ?>">

		<?php if (getValueFromString("Image", $data) !="" ) { ?>
		<img src="<?php print getValueFromString("Image", $data) ?>" class="mainImage" alt="Book cover" />
		<?php } ?>
		<h1 itemprop="name"><?php print $name ?></h1>
		<div class="metaData">
			By: <span class="author" itemprop="author">Johan M. Dahlgren</span>
			Genre: <span class="genre" itemprop="genre"><?php print getValueFromString("Genre", $data) ?></span>
			Pages: <span class="numberOfPages" itemprop="numberOfPages"><?php print getValueFromString("NumberOfPages", $data) ?></span>
			Ages: <span class="typicalAgeRange" itemprop="typicalAgeRange"><?php print getValueFromString("AgeRange", $data) ?></span>
		</div>
		<?php if (getValueFromString("Quote", $data) != null) { ?>
			<blockquote>
				<?php print formatText(getValueFromString("Quote", $data)) ?>
			</blockquote>
		<?php }
		print formatText(getValueFromString("Text", $data), true, true) ?>
		<div class="center bookLinksContainer">
			<?php
				$subPages = getEntities($id, 143);
				while (list ($pageId, $pageName, $pageStatus, $pageIcon, $pageType, $pageParentId, $pagePublishDate, $pageSortOrder, $pageNodeReference, $pageCode, $pageData) = mysql_fetch_row ($subPages))
				{
					?>
						<a href="index.php?entityId=<?php print $pageId ?>" class="button"><?php print $pageName ?></a>
					<?php
				}
			?>
		</div>
	</div>
</article>