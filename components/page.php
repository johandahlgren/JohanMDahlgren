<?php
$depth = 0;

function renderMenu ($aParentId) {
    $pages = getEntities($aParentId, 143);
    while (list ($id, $name, $state, $icon, $type, $parentId, $publishDate, $sortOrder, $nodeReference, $data) = mysql_fetch_row ($pages))
    {
?>
<a href="index.php?entityId=<?php print $id ?>" class="<?php if($id == $_REQUEST["entityId"]) {print("active");} ?>"><?php print $name ?></a>
<?php
    }
}

function renderCrumbtrail ($aEntityId, $aFirst) {
    $entity = getEntity($aEntityId);

    if ($aFirst) {
?>
<div id="crumbtrailContainer">
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
$image 				= "http://" . $_SERVER["SERVER_NAME"] . "/" . getValueFromString("Image", $rootPageData);

$pageEntity 		= getEntity($selectedEntity);
$pageData 			= $pageEntity["data"];

$pageTitle      	= $pageEntity["name"];
$pageDescription 	= strip_tags(formatText(getValueFromString("About", $pageData)));

if (getValueFromString("Title", $pageData) != "") {
    $pageTitle = getValueFromString("Title", $pageData);
}
?>
<!DOCTYPE html>
<html lang="en-GB" itemscope itemtype="http://schema.org/Blog">
    <head>
        <link href="http://fonts.googleapis.com/css?family=Krona+One" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="style/style<?php print $_REQUEST["css"] ?>.css?debug=<?php print time() ?>" media="screen" />

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script>var pageEntityId = <?php print $selectedEntity ?>;</script>
        <script src="js/main.js"></script>
		
        <link rel="canonical" href="<?php print getCurrentUrl() ?>"/>
        <link rel="icon" type="image/png" href="style/images/favicon.png" />
        <link rel="alternate" href="http://www.johanmdahlgren.com/rss.php" type="application/atom+xml" title="Johan M. Dahlgren">

        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="theme-color" content="#000">

        <title><?php print $pageTitle ?></title>

		<?php
			if ($_REQUEST["subEntityId"] != null) {
				$subEntity 			= getEntity($_REQUEST["subEntityId"]);
				$subEntityData 		= $subEntity["data"];
				$pageTitle 			= $subEntity["name"];
				$image 				= "http://" . $_SERVER["SERVER_NAME"] . "/" . getValueFromString("Image", $subEntityData);
				$pageDescription 	= strip_tags(formatText(getValueFromString("Text", $subEntityData)));
			}
		?>

        <meta property="og:image" content="<?php print $image ?>"/>
        <meta property="og:title" content="<?php print $pageTitle ?>"/>
        <meta property="og:description" content="<?php print $pageDescription ?>"/>
        <meta property="og:url" content="<?php print getCurrentUrl() ?>"/>
        <meta property="og:site_name" content="Johan M. Dahlgren author site"/>
        <meta property="og:type" content="blog"/>

        <!--[if IE]>
<style>
.ieOnly {display: block;}
</style>
<![endif]-->
    </head>
	<?php
		$extraStyle = "";
		if (getValueFromString("BackgroundImage", $data) != null) {
			$extraStyle = "style=\"background-image: url(" . getValueFromString("BackgroundImage", $data) . ")\"";
		}
		if ($_REQUEST["subEntityId"] != null) {
			$extraStyle = $extraStyle .  " class=\"hasCrumbtrail\"";
		}
	?>
	<body <?php print $extraStyle ?>>
		<nav id="topMenu">
			<a href="http://www.johanmdahlgren.com" class="siteHomeLink <?php if($selectedEntity == 116 || $selectedEntity == "") {print("active");} ?>">Home</a>
			<?php
				renderMenu(116);
				renderCrumbtrail($selectedEntity, true);
			?>
		</nav>
		
        <div itemscope itemtype="http://schema.org/Blog" class="parallax">
            <meta itemprop="name" content="<?php print $rootPageEntity["name"] ?>" />
            <meta itemprop="description" content="<?php print strip_tags(formatText(getValueFromString("About", $rootPageData))) ?>" />
            <meta itemprop="image" content="<?php print $image ?>" />
            <meta itemprop="author" content="Johan M. Dahlgren" />
            <meta itemprop="accountablePerson" content="Johan M. Dahlgren" />
            <meta itemprop="genre" content="Science fiction" />
            <meta itemprop="keywords" content="science fiction, noir, dystopia, action, violence" />
            <meta itemprop="typicalAgeRange" content="18-" />

            <div class="ieOnly">
                Please do not use older versions of Internet Explorer. At all. Ever.<br/>
                Upgrade to the latest version or better yet, <a href="https://www.google.com/chrome/browser/">download Chrome</a>.
            </div>
            <div id="overlay"></div>
			
			<div id="container">
				<?php
                    $template = getEntity(getValueFromString("Template", $pageData));
                    $templateCode = $template["code"];

                    if ($templateCode != "") {
                        eval ("?>" . $templateCode);
                    }
                ?>
            </div>
			<footer>
				<div class="innerContainer">
					<?php renderEntity(536) ?>
				</div>
			</footer>
        </div>

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