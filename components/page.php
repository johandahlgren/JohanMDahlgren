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
?>
<!DOCTYPE html>
<html lang="en-GB" itemscope itemtype="http://schema.org/Blog">
    <head>
        <link href="http://fonts.googleapis.com/css?family=Libre+Baskerville|Roboto:300" rel="stylesheet" type="text/css">
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
        <div id="header" style="background-image: url(<?php print $backgroundImage ?>);">
            <a href="http://www.johanmdahlgren.com" class="pageTitle">
                Johan M. Dahlgren
                <span class="pageTitleSmall">Aspiring science fiction author</span>
            </a>
        </div>
        <div id="content">
            <div id="topMenu">
                <a href="http://www.johanmdahlgren.com" class="<?php if($selectedEntity == 116 || $selectedEntity == "") {print("active");} ?>">Home</a>
                <?php
                    renderMenu(116);
                    renderCrumbtrail($selectedEntity, true);
                ?>
            </div>
            <div id="container">
                <?php
                    $template = getEntity(getValueFromString("Template", $pageData));
                    $templateCode = $template["code"];

                    if ($templateCode != "") {
                        eval ("?>" . $templateCode);
                    }
                ?>
            </div>
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