<?php

include "vendor/autoload.php";
include "lib/imgwork.php";
include "lib/tagwork.php";
include "lib/html.php";
include "lib/queryengine.php";

define("ROOTPATH", "/var/www/vps.sukovec.cz/");
define("PHPATH", ROOTPATH . "foto");
define("BASEPATH", ROOTPATH . "thumbs");
define("RAWPATH", "/home/suk/foto-backed/");

function inget($key) {
	return array_key_exists($key, $_GET);
}
