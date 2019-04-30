<?php
if (!array_key_exists("id", $_GET))
	throw new Exception("No ID in get");

require "base.php";

output_head();

echo "<ul>";

$dirl = new ImageFolderList();

$imgs = $dirl->getImgList($_GET["id"]);

for ($i = 0; $i < $imgs->count(); $i++) {
	$img = $imgs->getImage($i);
	$imgid = $img->getId();
	echo "<a href='tag.php?img=${imgid}'>";
	echo "<img width='256' src='getimg.php?type=thumb&amp;img=${imgid}' />";
	echo "</a>";
}

output_foot();
