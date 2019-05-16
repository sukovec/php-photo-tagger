<?php

require "base.php";

if (!inget("dir"))
	throw new Exception("dir not set");

$tagwor = new TagWork();
$imgs = new ImageFolderList();

foreach $($_POST as $imgid => $tag) {
	$img = $imgs->getImgById($imgid);

	$tags = $img->getTags();
	// pridat popripade zmenit prisusny tag
}

print_r($_GET);

print_r($_POST);
