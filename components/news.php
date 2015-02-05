<article id="article<?php print $id ?>" class="newsItem" itemscope itemtype="http://schema.org/BlogPosting">
	<div class="articleInnerContainer">
		<h2 itemprop="name"><?php print $name ?></h2>
		<div class="contentFooter">
			<span class="shortDate"><?php print getYMDshort($publishDate) ?></span>
		</div>
		<?php if (getValueFromString("Image", $data) != "") { ?>
			<a href="<?php print getValueFromString("Image", $data) ?>" class="newsImageLink colorbox" title="<?php print getValueFromString("ImageText", $data) ?>">
				<span class="newsImage" style="background-image: url(<?php print getValueFromString("Image", $data) ?>);" ></span>
			</a>
		<?php } ?>
		<span itemprop="articleBody">
			<?php print formatText(getValueFromString("Text", $data), true, true) ?>
		</span>
		<?php insertComments($id); ?>
	</div>
</article>