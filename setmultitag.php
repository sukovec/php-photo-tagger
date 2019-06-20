<?php

require "base.php";

onlyPost();

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

header("HTTP/1.1 307 See Other");
header("Location: index.php");
