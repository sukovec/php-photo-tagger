<?php

define("ROOTPATH", "/var/www/vps.sukovec.cz/");
define("PHPATH", ROOTPATH . "foto");
define("BASEPATH", ROOTPATH . "thumbs");
define("RAWPATH", "/home/suk/foto-backed/");

function inget($key) {
	return array_key_exists($key, $_GET);
}

function isPost() {
	return $_SERVER["REQUEST_METHOD"] == "POST";
}

function isGet() {
	return $_SERVER["REQUEST_METHOD"] == "GET";
}

function onlyPost() {
	if (!isPost())
		throw new Exception("Only POST");
}

function onlyGet() {
	if (!isGet())
		throw new Exception("Only GET");
}
