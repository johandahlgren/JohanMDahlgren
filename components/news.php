<h1><?php print $name ?></h1>
<div class="newsItem contentContainer">
    <div>
        <?php if (getValueFromString("Image", $data) != "") { ?>
        <a href="<?php print getValueFromString("Image", $data) ?>" class="colorbox" title="<?php print getValueFromString("ImageText", $data) ?>">
            <span class="newsImage" style="background-image: url(<?php print getValueFromString("Image", $data) ?>);" ></span>
        </a>
        <?php } ?>
        <p>
            <?php print formatText(getValueFromString("Text", $data), false) ?>
        </p>
        <div class="contentFooter">
            <span class="shortDate"><?php print getYMD($publishDate) ?></span>
            by <a rel="author" href="http://www.johanmdahlgren.com/index.php?entityId=214">Johan M. Dahlgren</a>
        </div>
    </div>

    <?php
    /*
		if (getValueFromString("AllowDiscussion", $data) == 281) {
    ?>
                <div class="discussionToggle">Show discussion</div>
				<div class="discussionDiv">
					<div id="disqus_thread"></div>
					<script type="text/javascript">
						var disqus_shortname = "johanmdahlgren"; // required: replace example with your forum shortname
						var disqus_identifier = "<?php print $id ?>";
						var disqus_url = "http://www.johanmdahlgren.com#!<?php print $id ?>";

						(function() {
							var dsq = document.createElement("script"); dsq.type = "text/javascript"; dsq.async = true;
							dsq.src = "//" + disqus_shortname + ".disqus.com/embed.js";
							(document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(dsq);
						})();
					</script>
					<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
					<a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
				</div>
			<?php
		}
        */
    ?>
</div>