<?php

require "base.php";

if (!inget("dir"))
	throw new Exception("dir not set");

$tagwor = new TagWork();
$imgs = new ImageFolderList();

foreach ($_POST as $imgid => $tag) {
	$img = $imgs->getImgById($imgid);

	$tagset = new ImageTagSet($img);

	print_r($tagset);

}

print_r($_GET);

print_r($_POST);
