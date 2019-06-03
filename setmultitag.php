<?php

require "base.php";

if (!inget("dir"))
	throw new Exception("dir not set");

$tagwork = new TagWork();
$imgs = new ImageFolderList();

foreach ($_POST as $imgid => $tag) {
	$img = $imgs->getImgById($imgid);

	$tagset = new ImageTagSet($img, $tagwork);

	

	echo "<h1>Image $imgid tagset, set it to $tag</h1>";
	print_r($tagset);

}

echo "<h1>GET</h1>";
print_r($_GET);

echo "<h1>POST</h1>";
print_r($_POST);
