<?php
if (!array_key_exists("img", $_GET))
	throw new Exception("No ID in get");

require "base.php";

output_head();

$dirl = new ImageFolderList();
$tgs = new TagWork();

$img = $dirl->getImgById($_GET["img"]);

/* TAGS */

echo form_start("settag.php?img=${_GET["img"]}");

$tags = $tgs->getTags($img->getTags());

foreach($tags as $tag) {
	$act = $tag->isChecked() ? " checked='checked' " : "";
	$tg = $tag->getBaseName();
	echo "<div class='cbox'><input type='checkbox' ${act} name='tag_${tg}' value='${tg}' id='tag_${tg}' /><label for='tag_${tg}'>${tg}</label></div>";
}
echo "<br /><div class='descript'><input type='text' name='image_description' value='", $img->getDesc(),"' /><input type='submit' value='yeah' /></div>";

echo form_end();


/* THUMBNAIL */
/* THUMBNAIL */

echo "<img src='getimg.php?type=thumb&amp;img=${_GET["img"]}' />";


/* DOWNLOAD LINKS */
/* DOWNLOAD LINKS */

echo "<div class='fotodown'>";
if ($img->isRaw()) {
	echo "<a href='getimg.php?type=raw&amp;img=${_GET["img"]}&amp;down=true'>RAW</a> | ";
}
echo "<a href='getimg.php?type=full&amp;img=${_GET["img"]}&amp;down=true'>DOWN</a> | ";
echo "<a href='getimg.php?type=full&amp;img=${_GET["img"]}'>FULL</a>";
echo "</div>";



/* NAVIGATION */
/* NAVIGATION */


echo "<div class='navig'>";
$next = $img->getNext();
$prev = $img->getPrev();
if ($prev) { 
	$previd = $prev->getID();
	echo "<a href='?img=${previd}'>&lt;&lt;</a>";
}

echo "<a href='list-folder.php?id=${_GET["img"]}'>THIS DAY</a>";
echo "<a href='index.php'>INDEX</a>";

if ($next) {
	$nextid = $next->getID();
	echo "<a href='?img=${nextid}'>&gt;&gt;</a>";
}
echo "</div>";

output_foot();
