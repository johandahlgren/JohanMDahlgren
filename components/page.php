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
	$imageCredit	 	= getValueFromString("BackgroundImageText", $pageData);

	if ($backgroundImage == "") {
		$backgroundImage 	= getValueFromString("BackgroundImage", $rootPageData);
	}

	if ($imageCredit == "") {
		$imageCredit 	= getValueFromString("BackgroundImageText", $rootPageData);
	}
?>
<!DOCTYPE html>
<html lang="en-GB" itemscope itemtype="http://schema.org/Blog">
	<head>
        <link rel="stylesheet" type="text/css" href="style/style<?php print $_REQUEST["css"] ?>.min.css?key=<?php print filemtime("style/style.min.css") ?>" media="screen" />
		<link rel="stylesheet" type="text/css" href="plugins/swipebox/swipebox.css" media="screen" />

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
	</head>
	<body>
        <div class="headerImage" style="background-image: url(<?php print $backgroundImage ?>);"></div>
		<div class="container">
			<div class="head">
				<a href="index.php" class="pageTitle">
					Johan M. Dahlgren
					<span class="pageTitleSmall">Aspiring science fiction author</span>
				</a>
			</div>
			<div id="placeholder1" class="headerMenuContainerPlaceHolder"></div>
            <div id="placeholder2" class="headerMenuContainerPlaceHolder"></div>
			<div class="headerMenuContainer">
				<span class="<?php if($selectedEntity == 116 || $selectedEntity == "") {print("active");} ?>">
					<a href="index.php">Home</a>
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
                 <div id="footer">
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
                        <div id="socialDiv">
                            <div id="fb-root"></div>
                            <script>(function(d, s, id) {
                                var js, fjs = d.getElementsByTagName(s)[0];
                                if (d.getElementById(id)) return;
                                js = d.createElement(s); js.id = id;
                                js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
                                fjs.parentNode.insertBefore(js, fjs);
                            }(document, "script", "facebook-jssdk"));</script>
                            <div class="fb-like" data-href="http://www.johanmdahlgren.com" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>

                            <script type="text/javascript">
								(function() {
								var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
								po.src = "https://apis.google.com/js/plusone.js";
								var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
								})();
							</script>
                            <div class="g-plusone" data-size="medium" data-href="http://www.johanmdahlgren.com"></div>

                            <a href="https://twitter.com/johanmdahlgren" class="twitter-follow-button" data-show-screen-name="false" data-show-count="true" data-lang="en"></a>
                            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
						</div>
                    </div>
                </div>
            </div>
			<div class="clearfix"></div>
		</div>

		<div class="backgroundImageText"><?php print $imageCredit ?></div>

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script type="text/javascript">var pageEntityId = <?php print $selectedEntity ?>;</script>
		<script src="js/main.min.js"></script>
		<script src="plugins/swipebox/jquery.swipebox.min.js"></script>
        <!--
		<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52df9d5a65c61acd"></script>
		<script type="text/javascript">
			addthis.layers({
				"theme" : "dark",
				"share" : {
					"position" : "left",
					"services" : "facebook,twitter,google_plusone_share",
					"offset" : {
						"top" : "250px"
					}
				},
				"follow" : {
					"services" : [
						{"service": "facebook", "id": "johanmdahlgren"},
						{"service": "twitter", "id": "johanmdahlgren"},
						{"service": "google_follow", "id": "108209146446457439052"}
					]
				}
			});
		</script>
        -->
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