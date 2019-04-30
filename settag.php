<?php

if (!array_key_exists("img", $_GET))
	throw new Exception("No ID in get");

require "base.php";

$dirl = new ImageFolderList();
$tgs = new TagWork();

$img = $dirl->getImgById($_GET["img"]);

$img->setDesc($_POST["image_description"]);

$tgs = array();
foreach($_POST as $key => $value) {
		if (substr($key, 0, 4) != "tag_") continue;

		$tgs[] = $value;
}

$img->setTags($tgs);
$img->save();


$next = $img->getNext();
header("HTTP/1.1 303 See Other");

if ($next) {
	$nextid = $next->getID();
	header("Location: tag.php?img=${nextid}");
}
else {
        header("Location: index.php");
}
