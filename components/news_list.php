<article id="article<?php print $id ?>" itemscope itemtype="http://schema.org/BlogPosting">
    <div class="divider"></div><div class="date"><time datetime="<?php print getYMDM2M($publishDate) ?>" itemprop="dateCreated"><?php print getYMDshort($publishDate) ?></time></div><div class="divider"></div>
    <h2 itemprop="name"><?php print $name ?></h2>
	<?php if (getValueFromString("Image", $data) != "") { ?>
		<a href="<?php print getValueFromString("Image", $data) ?>" class="newsImageLink colorbox" title="<?php print getValueFromString("ImageText", $data) ?>">
			<span class="newsImage" style="background-image: url(<?php print getValueFromString("Image", $data) ?>);" ></span>
		</a>
	<?php } ?>
	<span itemprop="articleBody">
		<?php print formatText(getValueFromString( "Text", $data)); ?>
	</span>
    <?php insertComments($id); ?>
</article>