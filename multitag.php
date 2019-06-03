<?php
require "base.php";

if (!inget("dir"))
	throw new Exception("No ID in get");

output_head();

$tgs = new TagWork();

if (!inget("tag")) {
	$tags = $tgs->getTags();

	foreach ($tags as $tag) {
		$base = $tag->getBaseName();
		echo "<div class='multitag'><h3>${base}</h3>";

		foreach($tag->getSubtags() as $sub) {
			$canotag = $sub->getNotation();
			$subname = $sub->getName();
			echo "<a href='multitag.php?dir=${_GET["dir"]}&amp;tag=${canotag}'>${subname}</a> | ";
		}

		echo "</div>";
	}
} else {
	$dirl = new ImageFolderList();
	$imgs = $dirl->getImgList($_GET["dir"]);

	echo form_start("setmultitag.php?dir=${_GET["dir"]}");

	for ($i = 0; $i < $imgs->count(); $i++) {
		$img = $imgs->getImage($i);
		$tagset = new ImageTagSet($img, $tgs);

		$imgid = $img->getId();
		
		if ($tagset->hasTag($_GET["tag"]))
			echo "ma tag";

		if ($tagset->hasFullTag($_GET["tag"]))
			echo "ma full tag";


		echo "<input type='hidden' name='${imgid}' value='RES:${_GET["tag"]}' />"; //this may not work in other languages or frameworks
		echo "<div class='imgcheck'>";
		echo "<input type='checkbox' name='${imgid}' value='SET:${_GET["tag"]}' id='tag_${imgid}' />";
		echo "<label for='tag_${imgid}'><img src='getimg.php?type=thumb&amp;img=${imgid}' width='175' /></label>";
		echo "</div>";
	}

	echo "<div><input type='submit' value='SET IT YEEEAAAH' /></div>";
	echo form_end();
}

output_foot();
