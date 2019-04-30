<?php

include "vendor/autoload.php";
include "imgwork.php";
include "tagwork.php";
include "html.php";
include "queryengine.php";

define("ROOTPATH", "/var/www/vps.sukovec.cz/");
define("PHPATH", ROOTPATH . "foto");
define("BASEPATH", ROOTPATH . "thumbs");
define("RAWPATH", "/home/suk/foto-backed/");

function load_tag_list() {
	return file('tags.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

function verpath($path) {
	$pth = realpath(BASEPATH . "/" . $path);
	if (!substr($pth, 0, strlen(BASEPATH)) == BASEPATH) die("WTF vole");
	return true;
}

function return_image($img) {
	verpath($img);
	header("Content-type: image/jpeg");
	header("Cache-Control:public");
	header("Expires: Mon, 25 Jun 2019 21:31:12 GMT←");

	readfile(BASEPATH . "/" . $img);
}

function inget($key) {
	return array_key_exists($key, $_GET);
}
