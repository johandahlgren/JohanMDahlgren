<?php
	header('Content-type: application/atom+xml;charset=UTF-8');
	require_once("cms/core.php");

	$sqlQuery = "SELECT UNIX_TIMESTAMP(publishDate) as publishDate FROM j4_entity WHERE type = 222 ORDER BY publishDate DESC LIMIT 1;";
	$latestNewsItem = dbGetSingleRow($sqlQuery);

	$news = getEntities(294, null, null, null, "DESC");
?>
<?php print "<" . "?xml version=\"1.0\" encoding=\"utf-8\"?" . ">" ?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
	<title type="text">Johan M. Dahlgren</title>
	<subtitle type="html">Latest news from science fiction author Johan M. Dahlgren</subtitle>
	<updated><?= date(DATE_ATOM, $latestNewsItem["publishDate"]) ?></updated>
	<id>http://www.johanmdahlgren.com/</id>
	<link rel="self" type="application/atom+xml" href="http://www.johanmdahlgren.com/rss.php"/>
	<rights>Copyright (c) 2014, Johan M. Dahlgren</rights>

	<?php
		while (list ($id, $name, $state, $icon, $type, $parentId, $publishDate, $sortOrder, $nodeReference, $code, $listCode, $data) = mysql_fetch_row ($news))
		{
			$url 	= "http://www.johanmdahlgren.com/index.php?entityId=328&amp;subEntityId=" . $id;
			//$id 	= substr($url, strpos($url, "//") + 2);
			//$id		= str_replace("/", ",", $id);

			$entryId 	= "http://www.johanmdahlgren.com," . date("Y-m-d", $publishDate) . ":" . $id . "/";
			?>
				<entry>
					<title type="html"><?= $name ?></title>
					<link rel="alternate" type="text/html" href="<?= $url ?>"/>
					<id><?= $entryId ?></id>
					<published><?= date(DATE_ATOM, $publishDate) ?></published>
					<updated><?= date(DATE_ATOM, $publishDate) ?></updated>
					<author>
						<name>Johan</name>
						<uri>http://www.johdanmdahlgren.com</uri>
					</author>
					<content type="html">
						<![CDATA[
							<p>
								<?php if (getValueFromString("Image", $data) != null) { ?>
									<img src="<?= getValueFromString("Image", $data) ?>" />
								<?php } ?>
								<?=  formatText(getValueFromString("Text", $data)) ?>
							</p>
						]]>
					</content>
					<?php
						/*
						if (getValueFromString("Image", $data) != null)
						{
							?>
								<media:content url="<?= getValueFromString("Image", $data) ?>" type="image/jpeg" height="91" width="130" />
							<?php
						} */
					?>
			</entry>
			<?
		}
	?>
</feed>