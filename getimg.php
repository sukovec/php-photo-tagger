<?php
require "base.php";

if (!inget("type") || !inget("img"))
	throw new Exception("fujuy");

$imgs = new ImageFolderList();
$img = $imgs->getImgById($_GET["img"]);


header("Cache-Control: public");
header("Expires: Mon, 25 Jun 2019 21:31:12 GMT←");

$fname = "";
switch($_GET["type"]) {
case "thumb":
	header("Content-type: image/jpeg");
	$fname = $img->getThumbPath();
	break;
case "full":
	header("Content-type: image/jpeg");
	$fname = $img->genFullres();
	break;
case "raw":
	if (!$img->isRaw()) {
		header("HTTP/1.1 406 Not Acceptable");
		die("Niet raw, niet rawfile");
	}
	header("Content-type: image/x-panasonic-rw2");
	$fname = $img->getRawPath();
	break;
default:
	throw new Exception("chyba");
}

if ((inget("down") && $_GET["down"] == "true") || $_GET["type"] == "raw")
	header("Content-Disposition: attachment; filename=" . basename($fname));
readfile($fname);
