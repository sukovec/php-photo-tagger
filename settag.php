<?php

require "base.php";

onlyPost();

if (!array_key_exists("img", $_GET))
	throw new Exception("No ID in get");

$dirl = new ImageFolderList();
$tgs = new TagWork();

$img = $dirl->getImgById($_GET["img"]);
$img->setDesc($_POST["image_description"]);


$tagset = new ImageTagSet($img, $tgs); // current set
$newset = array(); // newly set tags
foreach($_POST as $key => $value) {
		if (substr($key, 0, 4) != "tag_") continue;

		$bs = Tag::parseBaseName($value);
		$newset[$bs] = true;

		$tagset->setTag($value);
}


// loop through all tags and remove those unused
foreach ($tagset->getTags() as $bsname => $tag) {
	if (!array_key_exists($bsname, $newset)) {
		$tagset->removeTag($bsname);
	}
}

$tagset->saveTags();  // this will also call $img->save();

$next = $img->getNext();
header("HTTP/1.1 303 See Other");

if ($next) {
	$nextid = $next->getID();
	header("Location: tag.php?img=${nextid}");
}
else {
        header("Location: index.php");
}
