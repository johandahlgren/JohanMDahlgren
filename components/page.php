<?php
	$depth = 0;

	function renderMenu ($aParentId) {
		$pages = getEntities($aParentId, 143);
		while (list ($id, $name, $state, $icon, $type, $parentId, $publishDate, $sortOrder, $nodeReference, $data) = mysql_fetch_row ($pages))
		{
			?>
				<span id="menu_<?php print $id ?>" class="<?php if($id == $_REQUEST["entityId"]) {print("active");} ?>">
					<a href="index.php?entityId=<?php print $id ?>"><?php print $name ?></a>
				</span>
			<?php
		}
	}

	function renderCrumbtrail ($aEntityId, $aFirst) {
		$entity = getEntity($aEntityId);

		if ($aFirst) {
			?>
				<div class="crumbtrailContainer">
			<?php
		}

		if ($entity["parentId"] != 116 && $aEntityId != 116 && $entity["parentId"] != 394 && $aEntityId != 394) {
			$GLOBALS["depth"] = $GLOBALS["depth"] + 1;
			renderCrumbtrail ($entity["parentId"], false);
		}
		else
		{
			?>
				<script type="text/javascript">
					var topMenuSelection = "#menu_<?php print $aEntityId ?>";
				</script>
			<?php
		}

		if ($GLOBALS["depth"] > 0 && $entity["type"] == 143) {
			if ($aFirst) {
				if ($_REQUEST["subEntityId"] != null) {
					$subEntity = getEntity($_REQUEST["subEntityId"]);
					$entityName = $subEntity["name"];
				} else {
					$entityName = $entity["name"];
				}

				print $entityName;
			} else {
				?>
					<a href="index.php?entityId=<?php print $entity["id"] ?>" class="crumbtrail"><?php print $entity["name"] ?></a>
				<?php
			}
		}

		if ($aFirst) {
			?>
				</div>
			<?php
		}
	}

	$selectedEntity = $_REQUEST["entityId"];
	if ($selectedEntity == null) {
		$selectedEntity = 116;
	}

    $rootPageEntity 	= getEntity(116);
    $rootPageData 		= $rootPageEntity["data"];
    $headAuthorImage 	= getValueFromString("AuthorImage", $rootPageData);

	$pageEntity 		= getEntity($selectedEntity);
	$pageData 			= $pageEntity["data"];
	$backgroundImage 	= getValueFromString("BackgroundImage", $pageData);
    $footerImage 	    = getValueFromString("FooterImage", $pageData);
	$imageCredit	 	= getValueFromString("BackgroundImageText", $pageData);

	if ($backgroundImage == "") {
		$backgroundImage 	= getValueFromString("BackgroundImage", $rootPageData);
	}

    if ($footerImage == "") {
		$footerImage 	= getValueFromString("FooterImage", $rootPageData);
	}

	if ($imageCredit == "") {
		$imageCredit 	= getValueFromString("BackgroundImageText", $rootPageData);
	}
?>
<!DOCTYPE html>
<html lang="en-GB" itemscope itemtype="http://schema.org/Blog">
	<head>
        <link href="http://fonts.googleapis.com/css?family=Poiret+One|Raleway" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="style/style<?php print $_REQUEST["css"] ?>.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="plugins/colorbox/colorbox.css" media="screen" />

		<link rel="canonical" href="<?php print getCurrentUrl() ?>"/>
		<link rel="icon" type="image/png" href="style/images/favicon.png" />
		<link rel="alternate" href="http://www.johanmdahlgren.com/rss.php" type="application/atom+xml" title="Johan M. Dahlgren">

		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
		<meta name="mobile-web-app-capable" content="yes">

		<?php
			if ($_REQUEST["subEntityId"] != "") {
				$subEntity 	    = getEntity($_REQUEST["subEntityId"]);
				$name 		    = $subEntity["name"];
				$pageTitle 	    = $name;
                $description    = strip_tags(formatText(getValueFromString("Text", $subEntity["data"])));
				$image          = getValueFromString("Image", $subEntity["data"]);
			} else {
				$pageTitle      = $pageEntity["name"];
                $image          = $backgroundImage;
			}
		?>

		<title><?php print $pageTitle ?> | Johan M. Dahlgren</title>

		<meta property="og:image" content="<?php print $image ?>"/>
		<meta property="og:title" content="<?php print $name ?>"/>
        <meta property="og:description" content="<?php print $description ?>"/>
		<meta property="og:url" content="<?php print getCurrentUrl() ?>"/>
		<meta property="og:site_name" content="Johan M. Dahlgren author site"/>
		<meta property="og:type" content="blog"/>

		<meta itemprop="name" content="<?php print $name ?>">
		<meta itemprop="description" content="<?php print $description ?>">
		<meta itemprop="image" content="<?php print $image ?>">

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script type="text/javascript">var pageEntityId = <?php print $selectedEntity ?>;</script>
		<script src="js/main.min.js"></script>
		<script src="plugins/colorbox/colorbox.min.js"></script>
	</head>
	<body>
        <div class="headerImage" style="background-image: url(<?php print $backgroundImage ?>);">
            <!-- AddThis Button BEGIN -->
                <div class="addThisShare addthis_toolbox addthis_default_style addthis_32x32_style ">
                    <h3>Share this page</h3>
                    <a class="addthis_button_facebook"></a>
                    <a class="addthis_button_google_plusone_share"></a>
                    <a class="addthis_button_twitter"></a>
                    <a class="addthis_counter addthis_bubble_style"></a>
                </div>
                <script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
                <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52df9d5a65c61acd"></script>
            <!-- AddThis Button END -->
            <!-- AddThis Follow BEGIN -->
                <div class="addThisFollow addthis_toolbox addthis_default_style addthis_32x32_style ">
                    <h3>Follow</h3>
                    <a class="addthis_button_facebook_follow" addthis:userid="johanmdahlgren"></a>
                    <a class="addthis_button_google_follow" addthis:userid="+johanmdahlgrenauthor"></a>
                    <a class="addthis_button_twitter_follow" addthis:userid="johanmdahlgren"></a>
                    <a class="addthis_button_rss_follow" addthis:userid="http://www.johanmdahlgren.com/rss.php"></a>
                </div>
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52df9d5a65c61acd"></script>
            <!-- AddThis Follow END -->

            <div class="head">
				<a href="http://www.johanmdahlgren.com" class="pageTitle">
					Johan M. Dahlgren
					<span class="pageTitleSmall">Aspiring science fiction author</span>
				</a>
			</div>
        </div>
		<div class="container">
			<div id="placeholder1" class="headerMenuContainerPlaceHolder"></div>
            <div id="placeholder2" class="headerMenuContainerPlaceHolder"></div>
			<div class="headerMenuContainer">
				<span class="<?php if($selectedEntity == 116 || $selectedEntity == "") {print("active");} ?>">
					<a href="http://www.johanmdahlgren.com">Home</a>
				</span>
				<?php
					renderMenu(116);
				    renderCrumbtrail($selectedEntity, true);
                ?>
			</div>
            <div class="pageContent">
                <?php
                    $template = getEntity(getValueFromString("Template", $pageData));
                    $templateCode = $template["code"];

                    if ($templateCode != "") {
                        eval ("?>" . $templateCode);
                    }
                ?>
                </div>
            </div>
        <div class="clearfix"></div>

        <div id="footer" style="background-image: url(<?php print $footerImage ?>);">
			<div class="addthis_sharing_toolbox"></div>
            <!-- AddThis Button BEGIN -->
                <div class="addThisShare addthis_toolbox addthis_default_style addthis_32x32_style ">
                    <a class="addthis_button_facebook"></a>
                    <a class="addthis_button_google_plusone_share"></a>
                    <a class="addthis_button_twitter"></a>
                    <a class="addthis_counter addthis_bubble_style"></a>
                    <h3>Share this page</h3>
                </div>
                <script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
                <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52df9d5a65c61acd"></script>
            <!-- AddThis Button END -->
            <!-- AddThis Follow BEGIN -->
                <div class="addThisFollow addthis_toolbox addthis_default_style addthis_32x32_style ">
                    <a class="addthis_button_facebook_follow" addthis:userid="johanmdahlgren"></a>
                    <a class="addthis_button_google_follow" addthis:userid="+johanmdahlgrenauthor"></a>
                    <a class="addthis_button_twitter_follow" addthis:userid="johanmdahlgren"></a>
                    <a class="addthis_button_rss_follow" addthis:userid="http://www.johanmdahlgren.com/rss.php"></a>
                    <h3>Follow</h3>
                </div>
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52df9d5a65c61acd"></script>
            <!-- AddThis Follow END -->
			<div class="bottom">
				<div class="col100">
					<div class="right">
						<?php
							$childPages = getEntities(68, 143);
							while (list ($id, $name, $state, $icon, $type, $parentId, $publishDate, $sortOrder, $nodeReference, $code, $listCode, $data) = mysql_fetch_row ($childPages))
							{
								$books = getEntities($id, 134);

								while (list ($bookId, $bookName, $bookState, $bookIcon, $bookType, $bookParentId, $bookPublishDate, $bookSortOrder, $bookNodeReference, $bookCode, $bookListCode, $bookData) = mysql_fetch_row ($books))
								{
									?>
										<a href="index.php?entityId=<?php print $id ?>"><img class="bookCover" src="<?php print getValueFromString("SmallCover", $bookData) ?>" alt="Book cover of <?php print $bookName ?>" /></a>
									<?php
								}
							}
						?>
					</div>
					&copy; Johan M. Dahlgren<br/>
					<a href="http://www.facebook.com/johanmdahlgren">Facebook page</a><br/>
					<a href="https://plus.google.com/+Johanmdahlgrenauthor" rel="author">Google+ page</a><br/>
					<a href="http://www.twitter.com/johanmdahlgren">Twitter</a>
				</div>
			</div>
        </div>

		<div class="backgroundImageText"><?php print $imageCredit ?></div>

        <?php if ($_SESSION["loggedIn"] != true) { ?>
            <script type="text/javascript">
                var _gaq = _gaq || [];
                _gaq.push(["_setAccount", "UA-46178706-1"]);
                _gaq.push(["_trackPageview"]);
                (function() {
                    var ga = document.createElement("script"); ga.type = "text/javascript"; ga.async = true;
                    ga.src = ("https:" == document.location.protocol ? "https://" : "http://") + "stats.g.doubleclick.net/dc.js";
                    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ga, s);
                })();
            </script>
        <?php } ?>
	</body>
</html>