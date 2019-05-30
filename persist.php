<?php
require "base.php";

if (!inget("img"))
	throw new Exception("fujuy");

$imgs = new ImageFolderList();
$img = $imgs->getImgById($_GET["img"]);

$options = ['dir' => PHPATH, "ext" => ".persist" ];
$persists = new Flintstone\Flintstone('fotolinks', $options);

$uid = uniqid();

$data = Array( 
	"img" => $img->getId(),
	"seen" => { "full" => 0, "thumb" => 0, "raw" => 0 ],
	"adt" => array_key_exists("adt", $_GET) ? $_GET["adt"] : null
);

$persists->set($uid, $data);

header("HTTP/1.1 307 See Other");
header("Location: /persist/" . $uid);

