<?php

require "base.php";

if (!inget("id"))
	throw new Exception("Without id is no image");

$options = ['dir' => PHPATH, "ext" => ".persist" ];
$persists = new Flintstone\Flintstone('fotolinks', $options);

/*$data = Array( 
	"img" => $img->getId(),
	"seen" => 0, 
	"adt" => array_key_exists("adt", $_GET) ? $_GET["adt"] : null
);*/

if (!inget("type")) $_GET["type"] = "full";
$data = $persists->get($_GET["id"]);
$data["seen"][$_GET["type"]]++;
$persists->set($_GET["id"], $data);

$_GET["img"] = $data["img"];

include "getimg.php";
