<?php

require "base.php";

if (!inget("dir"))
	throw new Exception("dir not set");

$tagwork = new TagWork();
$imgs = new ImageFolderList();

foreach ($_POST as $imgid => $tag) {
	$img = $imgs->getImgById($imgid);

	$tagset = new ImageTagSet($img, $tagwork);

	

	$tagset->tagCommand($tag);
	$tagset->saveTags();

}
