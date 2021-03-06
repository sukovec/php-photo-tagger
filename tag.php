<?php
if (!array_key_exists("img", $_GET))
	throw new Exception("No ID in get");

if (array_key_exists("mywatchid", $_COOKIE))
	exec("mosquitto_pub -t '/watch/${_COOKIE["mywatchid"]}' -m '${_GET["img"]}' > /dev/null 2>&1 &");

require "base.php";

output_head();

$dirl = new ImageFolderList();
$tgs = new TagWork();

$img = $dirl->getImgById($_GET["img"]);

/* TAGS */

echo form_start("settag.php?img=${_GET["img"]}");

$tags = $tgs->getTags($img->getTags());

foreach($tags as $tag) {
	$basename = $tag->getBaseName();

	$act = $tag->isChecked() ? " checked='checked' " : "";

	$sub = $tag->getSelectedSubtag();
	$subname = "---";
	if ($sub !== null)
		$subname = $sub->getName();

	$display = $basename;
	if ($tag->haveSubs()) 
		$display = $basename . " (" . $subname . ")";

	if ($tag->isHidden()) {
	       	if ($tag->isChecked())
			echo "<input type='hidden' name='tag_{$basename}' value='${basename}' />";
		// else - noop
	}
	else {
		$onload = "";
		$key = $tag->getKey();
		$keyinfo = "";
		if ($key !== null) {
			$data = " data-asckey='$key'";
			$keyinfo = "<span class='asckeyinfo'>${key}: </span>";
		}

		echo "<div class='cbox'><input ${data}type='checkbox' ${act} name='tag_${basename}' value='${basename}' id='tag_${basename}' /><label for='tag_${basename}'>${keyinfo}${display}</label></div>";
	}

}
echo "<script>asociateKeyPresses()</script>";
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
echo "<a href='getimg.php?type=full&amp;img=${_GET["img"]}'>FULL</a> | ";
echo "<a href='persist.php?img=${_GET["img"]}'>PERSIST</a>";
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
