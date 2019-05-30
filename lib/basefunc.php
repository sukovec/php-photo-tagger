<?php

define("ROOTPATH", "/var/www/vps.sukovec.cz/");
define("PHPATH", ROOTPATH . "foto");
define("BASEPATH", ROOTPATH . "thumbs");
define("RAWPATH", "/home/suk/foto-backed/");

function inget($key) {
	return array_key_exists($key, $_GET);
}
