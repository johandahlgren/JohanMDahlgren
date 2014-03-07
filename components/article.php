<div class="article contentContainer">
	<?php
		if (getValueFromString("Display", $data) == 290) {
			?>
				<div class="narrowContainer">
					<div class="narrow">
			<?php
		}
	?>
	<h1><?php print formatText($name, false, false) ?></h1>
	<?php if (getValueFromString("Image", $data) != "") { ?>
		<img src="<?php print getValueFromString("Image", $data) ?>" alt="Article main image" />
	<?php } ?>
	<?php print formatText(getValueFromString("Text", $data)) ?>
	<?php
		if (getValueFromString("Display", $data) == 290) {
			?>
					</div>
				</div>
			<?php
		}
	?>

	<?php generateSharingLinks() ?>

	<?php
		if (getValueFromString("AllowDiscussion", $data) == 278) {
			?>
				<div class="discussionDiv">
					<div id="disqus_thread"></div>
					<script type="text/javascript">
						/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
						var disqus_shortname = "johanmdahlgren"; // required: replace example with your forum shortname
						var disqus_identifier = "<?php print $id ?>";
						var disqus_url = "http://www.johanmdahlgren.com#!<?php print $id ?>";

				/* * * DO NOT EDIT BELOW THIS LINE * * */
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
	?>
</div>